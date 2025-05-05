<?php
// Koneksi ke database cafedb
$host = "localhost";
$user = "root";
$password = "";
$database = "cafedb";

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query ambil data reservasi
$sql = "SELECT nama, email, no_hp, tanggal, waktu, jumlah_orang FROM tb_reservasi ORDER BY tanggal DESC, waktu DESC";
$result = $conn->query($sql);
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
        <!-- Bar Judul Notification -->
        <div class="notif-title mb-3">Notification</div>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                echo '<div class="notif-item">
                    <div class="info-kafe">
                        <div class="kafe-name"><?= htmlspecialchars($row['cafe_name']) ?></div>
                        <div class="kafe-address">Tanggal: <?= htmlspecialchars($row['date_reservation']) ?> | Jam: <?= htmlspecialchars($row['time_from']) ?> - <?= htmlspecialchars($row['time_to']) ?></div>
                        <div class="kafe-rating">Status: <?= htmlspecialchars($row['status']) ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            echo '<div class="notif-item">Tidak ada notifikasi.</div>'
        <?php endif; ?>
    </div>
</body>
</html>
