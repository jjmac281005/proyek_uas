<?php
session_start();
header('Content-Type: application/json');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "fail", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seat = $_POST['seat'] ?? null;
    $cafe = $_POST['cafe'] ?? null;
    $timeFrom = $_POST['time_from'] ?? null;
    $timeTo = $_POST['time_to'] ?? null;
    $date_reservation = $_POST['date_reservation'] ?? null;
    $status = 'pending'; 


    // Validasi
    if (!$seat || !$cafe || !$timeFrom || !$timeTo || !$date_reservation) {
        echo json_encode(["status" => "fail", "message" => "Missing data"]);
        exit;
    }

    $conn = new mysqli("localhost", "root", "", "cafe_reservation");

    if ($conn->connect_error) {
        echo json_encode(["status" => "fail", "message" => "DB connection error"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO reservation (user_id, cafe_name, seat_number, time_from, time_to, date_reservation, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("issssss", $user_id, $cafe, $seat, $timeFrom, $timeTo, $date_reservation, $status);


    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "fail", "message" => "DB insert error"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "fail", "message" => "Invalid request method"]);
}
?>
