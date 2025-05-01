<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    echo "<script>alert('Akses ditolak. Silakan login sebagai customer.'); window.location.href='login.php';</script>";
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Ambil data notifikasi dari database berdasarkan user_id
$stmt = $conn->prepare("SELECT c.name AS cafe_name, r.date, r.time, r.status 
                        FROM reservations r
                        JOIN cafes c ON r.cafe_id = c.id
                        WHERE r.customer_id = ?
                        ORDER BY r.date DESC, r.time DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

$stmt->close();
$conn->close();
?>
