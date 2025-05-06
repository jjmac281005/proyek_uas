<?php
$conn_cafe = new mysqli("localhost", "root", "", "cafedb"); // Ganti 'cafedb' dengan nama database cafe
if ($conn_cafe->connect_error) {
    die("Connection failed (cafedb): " . $conn_cafe->connect_error);
}
?>
