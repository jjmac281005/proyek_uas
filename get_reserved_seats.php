<?php
$koneksi = new mysqli("localhost", "root", "", "cafe_reservation");

date_default_timezone_set('Asia/Jakarta'); // Sesuaikan timezone jika perlu
$current_time = date('Y-m-d H:i:s');

$sql = "
    SELECT seat_number, date_reservation, time_to, status
    FROM reservation
    WHERE status != 'Canceled'
";

$result = $koneksi->query($sql);
$seats = [];

while ($row = $result->fetch_assoc()) {
    // Gabungkan tanggal dan waktu selesai reservasi
    $reservation_end = $row['date_reservation'] . ' ' . $row['time_to'];

    // Jika reservasi belum selesai, tandai kursi sebagai "terisi"
    if ($reservation_end > $current_time) {
        $seats[] = ['seat_number' => $row['seat_number']];
    }
}

header('Content-Type: application/json');
echo json_encode($seats);
?>
