<?php
$koneksi = new mysqli("localhost", "root", "", "cafe_reservation");

$result = $koneksi->query("SELECT seat_number FROM reservation");
$seats = [];

while ($row = $result->fetch_assoc()) {
    $seats[] = $row;
}

header('Content-Type: application/json');
echo json_encode($seats);
?>
