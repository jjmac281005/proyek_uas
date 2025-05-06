<?php
$conn = new mysqli("localhost", "root", "", "cafe_reservation"); // Ganti 'maindb' dengan nama database utama kamu
if ($conn->connect_error) {
    die("Connection failed (main): " . $conn->connect_error);
}
?>
