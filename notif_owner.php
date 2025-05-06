<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Mengambil user_id dari sesi login

// Koneksi ke database cafe_reservation
$host = "localhost";
$user = "root";
$password = "";
$database = "cafe_reservation";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Koneksi ke database cafe_db untuk ambil username dari admin yang sedang login
$conn_cafe = new mysqli($host, $user, $password, "cafedb");

if ($conn_cafe->connect_error) {
    die("Koneksi ke database cafe_db gagal: " . $conn_cafe->connect_error);
}

// Ambil username yang sedang login berdasarkan user_id (asumsikan user_id adalah primary key yang bisa dipakai untuk mencari username)
$sql_user = "SELECT username FROM admin WHERE id = ?";
$stmt_user = $conn_cafe->prepare($sql_user);
$stmt_user->bind_param("i", $user_id); // Menggunakan user_id untuk mendapatkan username yang sesuai
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user && $result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $logged_in_username = $row_user['username']; // Menyimpan username yang sedang login
} else {
    die("Pengguna tidak ditemukan.");
}

// Query ambil data reservasi berdasarkan user_id, dikelompokkan berdasarkan cafe_name
$sql = "SELECT dashboard_owner.username, date_reservation, 
        time_from, time_to, status, cafe_name
        FROM dashboard_owner
        ORDER BY cafe_name, date_reservation DESC, time_from DESC";

$stmt = $conn->prepare($sql);
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
    <div class="logo"><a href="dashboard_owner.php"><img src="gambar/logo_text.png" alt="text Logo"></a></div>
    <div class="nav-buttons">
        <button class="nav-btn"><a href="notif_owner.php"><img src="gambar/icons8-notifications-64.png" width="33" height="33"></a></button>
        <button class="nav-btn"><a href="profile_owner.php"><img src="gambar/icons8-male-user-48.png" width="30" height="30"></a></button>
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

            // Cek apakah cafeName di tabel dashboard_owner sesuai dengan username yang sedang login
            if ($cafeName === $logged_in_username): // Cek apakah cafe_name sama dengan username yang login
                ?>
                <div class="notif-item">
                    <div class="info-kafe">
                        <div class="kafe-name"><?= $cafeName ?></div>
                        <?php if ($now <= $end): ?>
                            <div class="kafe-message" style="color: bisque;">
                                Reservasi telah dilakukan atas nama <strong><?= $username ?></strong> pada tanggal 
                                <strong><?= $date ?></strong> dari pukul <strong><?= $timeFrom ?> - <?= $timeTo ?></strong>.<br>
                                Pastikan datang tepat waktu ya!
                            </div>
                        <?php endif; ?>                    

                        <?php if ($now > $end): ?>
                            <div class="kafe-expired" style="color: bisque;">
                                Reservasi atas nama <strong><?= $username ?></strong> pada tanggal 
                                <strong><?= $date ?></strong> telah <strong style="color: red;">EXPIRED</strong>.<br>
                                Pastikan datang tepat waktu agar tidak expired ya!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            endif; // End of cafe_name check
        endwhile;
    else:
        ?>
        <div class="notif-item">Tidak ada notifikasi.</div>
    <?php endif; ?>
</div>
</body>
</html>
