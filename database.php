<?php
$host = "localhost";
$user = "root";  
$pass = "";      
$dbname = "cafe_reservation";

$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}
?>
