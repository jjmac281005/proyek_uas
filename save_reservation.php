<?php
header('Content-Type: application/json');

// Memeriksa apakah data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST request
    $seat = isset($_POST['seat']) ? $_POST['seat'] : null;
    $cafe = isset($_POST['cafe']) ? $_POST['cafe'] : null;
    $timeFrom = isset($_POST['time_from']) ? $_POST['time_from'] : null;
    $timeTo = isset($_POST['time_to']) ? $_POST['time_to'] : null;
    $date_reservation = isset($_POST['date_reservation']) ? $_POST['date_reservation'] : null;

    // Validasi data
    if (!$seat || !$cafe || !$timeFrom || !$timeTo) {
        echo json_encode(["status" => "fail", "message" => "Missing data"]);
        exit;
    }

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "cafe_reservation"); // ganti dengan nama database kamu

    if ($conn->connect_error) {
        echo json_encode(["status" => "fail", "message" => "DB connection error"]);
        exit;
    }

    // Simpan ke tabel
    $stmt = $conn->prepare("INSERT INTO reservations (cafe_name, seat_number, time_from, time_to, date_reservation, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $cafe, $seat, $timeFrom, $timeTo, $date_reservation);

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
