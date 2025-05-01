<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    echo "<script>alert('Akses ditolak. Silakan login sebagai customer.'); window.location.href='login.php';</script>";
    exit();
}

// Ambil rekomendasi kafe dari database (misalnya semua kafe dengan rating > 4.0)
$query = "SELECT id, name, address, rating, cuisine, image_path FROM cafes WHERE rating >= 4.0 ORDER BY rating DESC";
$result = $conn->query($query);

$cafes = [];
while ($row = $result->fetch_assoc()) {
    $cafes[] = $row;
}

$conn->close();
?>
