<?php
header('Content-Type: application/json');

// Memeriksa apakah data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST request
    $seat = isset($_POST['seat']) ? $_POST['seat'] : null;
    $cafe = isset($_POST['cafe']) ? $_POST['cafe'] : null;
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $timeFrom = isset($_POST['time_from']) ? $_POST['time_from'] : null;
    $timeTo = isset($_POST['time_to']) ? $_POST['time_to'] : null;
    $date_reservation = isset($_POST['date_reservation']) ? $_POST['date_reservation'] : null;
    $status = 'Confirmed'; 


    // Validasi data
    if (!$seat || !$cafe || !$timeFrom || !$timeTo || !$username || !$date_reservation) {
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
    $stmt = $conn->prepare("INSERT INTO dashboard_owner (username, cafe_name, seat_number, time_from, time_to, date_reservation, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssss", $username, $cafe, $seat, $timeFrom, $timeTo, $date_reservation, $status);

    $stmu = $conn->prepare("INSERT INTO reservation (cafe_name, seat_number, time_from, time_to, date_reservation, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmu->bind_param("ssssss", $cafe, $seat, $timeFrom, $timeTo, $date_reservation, $status);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "fail", "message" => "DB insert error"]);
    }

    if ($stmu->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "fail", "message" => "DB insert error"]);
    }

    $stmt->close();
    $stmu->close();
    $conn->close();
} else {
    echo json_encode(["status" => "fail", "message" => "Invalid request method"]);
}
?>
