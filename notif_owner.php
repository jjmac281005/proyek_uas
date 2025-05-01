<?php
include 'db.php';
session_start();

// Pastikan hanya owner yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    echo "<script>alert('Akses ditolak. Silakan login sebagai pemilik.'); window.location.href = 'login_owner.php';</script>";
    exit();
}

// Ambil notifikasi dari database
$owner_id = $_SESSION['user_id'];
$sql = "SELECT customer_name, date, time, status FROM notifications WHERE owner_id = ? ORDER BY date DESC, time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();

// Simpan semua data notifikasi
$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

$stmt->close();
$conn->close();
?>
