
<?php
$koneksi = new mysqli("localhost", "root", "", "cafedb");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$cafes = $koneksi->query("SELECT * FROM cafes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SIT & SIP</title>
    <link rel="stylesheet" href="dashboard2.css">
    <style>
        .logo-box {
            width: 250px;
            height: 250px;
            background-color: #c49a6c;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .content-box {
            width: 200px;
            height: 200px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
            flex: 0 0 auto;
        }

        .content-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<header class="navbar">
    <div class="logo">
        <a href="reserve_details.php"><img src="gambar/logo_text.png" alt="text Logo"></a>
    </div>
    <div class="nav-buttons">
        <button class="nav-btn"><a href="search.html"><img src="gambar/icons8-search-50.png" width="30"></a></button>
        <button class="nav-btn"><a href="notif.html"><img src="gambar/icons8-notifications-64.png" width="30"></a></button>
        <button class="nav-btn"><a href="profile.php"><img src="gambar/icons8-male-user-48.png" width="30"></a></button>
    </div>
</header>

<main class="content-wrapper">
    <div class="reservation-box">
        <div class="logo-box">
            <img src="gambar/ALLIGATOR/LOGO.jpeg" alt="Logo Kafe">
        </div>
        <div class="reservation-info">
            <p>Reservations going on..</p>
            <p>Time of reservation: 13:00 PM - 15:00 PM</p>
        </div>
    </div>

    <section class="content-section">
        <h2>Recommendations</h2>
        <div class="scroll-container">
            <button class="prev-btn">&#10094;</button>
            <div class="content">
                <?php while($cafe = $cafes->fetch_assoc()): ?>
                    <div class="content-box" onclick="window.location.href='cafedetails.php?id=<?= $cafe['id'] ?>'">
                        <img src="<?= htmlspecialchars($cafe['logo']) ?>" alt="<?= htmlspecialchars($cafe['name']) ?>">
                    </div>
                <?php endwhile; ?>
            </div>
            <button class="next-btn">&#10095;</button>
        </div>
    </section>
</main>
</body>
</html>
