<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "cafedb");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data dari tabel cafes
$query = "SELECT name, logo FROM cafes";
$result = $koneksi->query($query);
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
        <button class="nav-btn"><a href="notif.html"><img src="gambar/icons8-notifications-64.png" width="33" height="33"></a></button>
        <button class="nav-btn"><a href="profile.php"><img src="gambar/icons8-male-user-48.png" width="30" height="30"></a></button>
    </div>
</header>

<main class="content-wrapper">
    <section class="content-section">
        <h2>No reservations</h2>
    </section>

    <!-- Section Recommendations -->
    <section class="content-section">
        <h2>Recommendations</h2>
        <div class="scroll-container">
            <button class="prev-btn">&#10094;</button>
            <div class="content">
                <?php
                if ($result->num_rows > 0) {
                    // Reset pointer and loop 3 kali untuk 3 section
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="content-box">';
                        echo '<img src="' . $row['logo'] . '" alt="' . $row['name'] . '">';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <button class="next-btn">&#10095;</button>
        </div>
    </section>

    <!-- Section Recent Visits -->
    <section class="content-section">
        <h2>Recent visits</h2>
        <div class="scroll-container">
            <button class="prev-btn">&#10094;</button>
            <div class="content">
                <?php
                $result->data_seek(0); // Reset pointer
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="content-box">';
                    echo '<img src="' . $row['logo'] . '" alt="' . $row['name'] . '">';
                    echo '</div>';
                }
                ?>
            </div>
            <button class="next-btn">&#10095;</button>
        </div>
    </section>

    <!-- Section Favorites -->
    <section class="content-section">
        <h2>Favorites</h2>
        <div class="scroll-container">
            <button class="prev-btn">&#10094;</button>
            <div class="content">
                <?php
                $result->data_seek(0); // Reset pointer
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="content-box">';
                    echo '<img src="' . $row['logo'] . '" alt="' . $row['name'] . '">';
                    echo '</div>';
                }
                ?>
            </div>
            <button class="next-btn">&#10095;</button>
        </div>
    </section>
</main>
</body>
</html>
