<?php
session_start();
include 'database.php';

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Redirect jika belum login
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total = 0;

$today = date('Y-m-d');

// Gunakan prepared statement agar aman dari SQL injection
$sql = "SELECT date_reservation, time_to 
        FROM reservation 
        WHERE user_id = ? 
        AND status IN ('confirmed', 'pending') 
        AND date_reservation >= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$result = $stmt->get_result();

date_default_timezone_set('Asia/Jakarta');
$now = new DateTime();
$total = 0;

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $reservationEnd = new DateTime($row['date_reservation'] . ' ' . $row['time_to']);
        if ($now <= $reservationEnd) {
            $total++; // hanya tambahkan jika belum lewat time_to
        }
    }
} else {
    echo "Query error: " . $conn->error;
    exit;
}

$stmt->close();
$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Main</title>
    <link rel="stylesheet" href="dashboard_main.css">
    <style>
        .content-box {
            width: 150px;
            height: 150px;
            background-color: #f8f8f8;
            border-radius: 10px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            margin: 30px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .content-box:hover {
            transform: scale(1.05);
        }

        .content-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .content {
            display: flex;
            gap: 30px;
            overflow-x: auto;
            padding: 10px;
        }

        .scroll-container {
            display: flex;
            align-items: center;
        }

        .prev-btn, .next-btn {
            background-color: #ccc;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<header class="navbar">
    <div class="logo"><a href="reserve_details.php"><img src="gambar/logo_text.png" alt="text Logo"></a></div>
    <div class="nav-buttons">
        <button class="nav-btn"><a href="search.html"><img src="gambar/icons8-search-50.png" width="30" height="30"></a></button>
        <button class="nav-btn"><a href="notif.php"><img src="gambar/icons8-notifications-64.png" width="33" height="33"></a></button>
        <button class="nav-btn"><a href="profile.php"><img src="gambar/icons8-male-user-48.png" width="30" height="30"></a></button>
    </div>
</header>

<main class="content-wrapper">
    <section class="content-section">
        <h2><?= $total ?> reservations</h2>
    </section>

    <!-- Section Recommendations -->
    <section class="content-section" onclick="goToDetails('recommendations')">
            <h2>Recommendations</h2>
            <div class="scroll-container">
                <button class="prev-btn">&#10094;</button>
                <div class="content">
                    <div class="content-box" onclick="goToDetails('recommendations')">
                        <img src="gambar/KOORA/69e5c73f-0143-497f-9bc8-f47b3657c628.png" alt="Photo 2">
                    </div>
                    <div class="content-box" onclick="goToDetails('recommendations')">
                        <img src="gambar/ALLIGATOR/WhatsApp Image 2025-03-03 at 17.55.10 (1).jpeg" alt="Photo 1">
                    </div>
                    <div class="content-box" onclick="goToDetails('recommendations')">
                        <img src="gambar/OMOTESANDO/WhatsApp Image 2025-03-03 at 18.14.15.jpeg" alt="Photo 3">
                    </div>
                    <div class="content-box" onclick="goToDetails('recommendations')">
                        <img src="gambar/POTTE/WhatsApp Image 2025-03-03 at 18.20.32 (2).jpeg" alt="Photo 4">
                    </div>
                </div>
                <button class="next-btn">&#10095;</button>
            </div>
        </section>

        <!-- Recent Visits Section -->
        <section class="content-section" onclick="goToDetails('recent')">
            <h2>Recent visits</h2>
            <div class="scroll-container">
                <button class="prev-btn">&#10094;</button>
                <div class="content">
                    <div class="content-box" onclick="goToDetails('recent')">
                        <img src="gambar/ALLIGATOR/WhatsApp Image 2025-03-03 at 17.55.10 (1).jpeg" alt="Photo 1">
                    </div>
                    <div class="content-box" onclick="goToDetails('recent')">
                        <img src="gambar/OMOTESANDO/WhatsApp Image 2025-03-03 at 18.14.15.jpeg" alt="Photo 3">
                    </div>
                    <div class="content-box" onclick="goToDetails('recent')">
                        <img src="gambar/KOORA/69e5c73f-0143-497f-9bc8-f47b3657c628.png" alt="Photo 2">
                    </div>
                    <div class="content-box" onclick="goToDetails('recent')">
                        <img src="gambar/POTTE/WhatsApp Image 2025-03-03 at 18.20.32 (2).jpeg" alt="Photo 4">
                    </div>
                </div>
                <button class="next-btn">&#10095;</button>
            </div>
        </section>

        <!-- Favorite Section -->
        <section class="content-section" onclick="goToDetails('favorite')">
            <h2>Favorites</h2>
            <div class="scroll-container">
                <button class="prev-btn">&#10094;</button>
                <div class="content">
                    <div class="content-box" onclick="goToDetails('favorite')">
                        <img src="gambar/OMOTESANDO/WhatsApp Image 2025-03-03 at 18.14.15.jpeg" alt="Photo 3">
                    </div>
                    <div class="content-box" onclick="goToDetails('favorite')">
                        <img src="gambar/KOORA/69e5c73f-0143-497f-9bc8-f47b3657c628.png" alt="Photo 2">
                    </div>
                    <div class="content-box" onclick="goToDetails('favorite')">
                        <img src="gambar/POTTE/WhatsApp Image 2025-03-03 at 18.20.32 (2).jpeg" alt="Photo 4">
                    </div>
                    <div class="content-box" onclick="goToDetails('favorite')">
                        <img src="gambar/ALLIGATOR/WhatsApp Image 2025-03-03 at 17.55.10 (1).jpeg" alt="Photo 1">
                    </div>
                </div>
                <button class="next-btn">&#10095;</button>
            </div>
        </section>
    </section>
</main>

<script>
        function goToDetails(category) {
            window.location.href = `${category}.html`;
        }
    </script>
</body>
</html>
