<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "cafe_reservation";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query ambil data reservasi berdasarkan user_id
$sql = "SELECT users.username, reservation.date_reservation, 
        reservation.time_from, reservation.time_to, reservation.status, reservation.cafe_name
        FROM reservation
        JOIN users ON reservation.user_id = users.id
        WHERE users.id = ?
        ORDER BY reservation.date_reservation DESC, reservation.time_from DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi</title>
    <link rel="stylesheet" href="notif.css">
</head>
<body>
<header class="navbar">
    <div class="logo"><a href="dashboard_main.php"><img src="gambar/logo_text.png" alt="text Logo"></a></div>
    <div class="nav-buttons">
        <button class="nav-btn"><a href="search.html"><img src="gambar/icons8-search-50.png" width="30" height="30"></a></button>
        <button class="nav-btn"><a href="notif.php"><img src="gambar/icons8-notifications-64.png" width="33" height="33"></a></button>
        <button class="nav-btn"><a href="profile.php"><img src="gambar/icons8-male-user-48.png" width="30" height="30"></a></button>
    </div>
</header>

<div class="container mt-4">
    <div class="notif-title mb-3">Notification</div>

    <?php
    date_default_timezone_set('Asia/Jakarta'); // Ubah sesuai timezone server
    $now = new DateTime();

    if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
            $date = $row['date_reservation'];
            $timeFrom = $row['time_from'];
            $timeTo = $row['time_to'];

            $start = new DateTime("$date $timeFrom");
            $end = new DateTime("$date $timeTo");
            $username = htmlspecialchars($row['username']);
            $cafeName = htmlspecialchars($row['cafe_name']);
            ?>
            <div class="notif-item">
                <div class="info-kafe">
                    <div class="kafe-name"><?= $cafeName ?></div>
                    <?php if ($now <= $end): ?>
                        <div class="kafe-message" style="color: bisque">
                            Reservasi telah dilakukan atas nama <strong><?= $username ?></strong> pada tanggal 
                            <strong><?= $date ?></strong> dari pukul <strong><?= $timeFrom ?> - <?= $timeTo ?></strong>.<br>
                            Pastikan datang tepat waktu ya!
                        </div>
                    <?php endif; ?>

                    <?php if ($now > $end): ?>
                        <div class="kafe-expired" style="color: bisque;">
                            Reservasi atas nama <strong><?= $username ?></strong> pada tanggal 
                            <strong><?= $date ?></strong> telah <strong style="color: red">EXPIRED</strong>.<br>
                            Pastikan datang tepat waktu agar tidak expired ya!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="notif-item">Tidak ada notifikasi.</div>
    <?php endif; ?>
</div>
</body>
</html>
