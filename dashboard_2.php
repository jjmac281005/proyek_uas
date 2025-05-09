
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Main</title>
    <link rel="stylesheet" href="dashboard2.css">
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
     }
 
     .content-box img {
         max-width: 100%; 
         max-height: 100%; 
         object-fit: cover; 
         border-radius: 8px;
     }
 
     .content {
         display: flex;
         justify-content: space-between; 
         gap: 10px; 
         overflow-x: auto; 
         padding: 10px;
     }
 
     .content-box {
         width: 190px; 
         height: 190px;
         background-color: #f8f8f8;
         border-radius: 10px;
         text-align: center;
         display: flex;
         justify-content: center;
         align-items: center;
         overflow: hidden;
     }
 
     .content-box img {
         max-width: 100%;
         max-height: 100%;
         object-fit: cover;
         border-radius: 8px;
     }

     .logo-box {
        width: 200px; /* Sesuaikan ukuran logo */
        height: 200px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
    }

    .logo-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain; /* Memastikan gambar pas dalam kotak */
        border-radius: 10px;
    }
     </style>
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

    <!-- Kontainer utama yang bisa di-scroll secara vertikal -->
    <main class="content-wrapper">
        <div class="reservation-box"><a href="reserve_details.php">
            <div class="logo-box">
                <img src="gambar/ALLIGATOR/LOGO.jpeg" alt="Logo Kafe"></a>
            </div>
            <div class="reservation-info">
                <p>Reservations going on..</p>
                <p>Time of reservation: 13:00 PM - 15:00 PM</p>
            </div>
        </div>      

        <!-- Section 2 -->
        <section class="content-section" onclick="goToDetails('123')">
            <h2>Recommendations</h2>
            <div class="scroll-container">
                <button class="prev-btn">&#10094;</button>
                <div class="content">
                    <div class="content-box">
                        <img src="gambar/KOORA/69e5c73f-0143-497f-9bc8-f47b3657c628.png" alt="Photo 2">
                    </div>
                    <div class="content-box">
                        <img src="gambar/ALLIGATOR/WhatsApp Image 2025-03-03 at 17.55.10 (1).jpeg" alt="Photo 1">
                    </div>
                    <div class="content-box">
                        <img src="gambar/OMOTESANDO/WhatsApp Image 2025-03-03 at 18.14.15.jpeg" alt="Photo 3">
                    </div>
                    <div class="content-box">
                        <img src="gambar/POTTE/WhatsApp Image 2025-03-03 at 18.20.32 (2).jpeg" alt="Photo 4">
                    </div>
                </div>
                <button class="next-btn">&#10095;</button>
            </div>
        </section>

        <!-- Section 3 -->
        <section class="content-section">
            <h2>Recent visits</h2>
            <div class="scroll-container">
                <button class="prev-btn">&#10094;</button>
                <div class="content">
                    <div class="content-box">
                        <img src="gambar/ALLIGATOR/WhatsApp Image 2025-03-03 at 17.55.10 (1).jpeg" alt="Photo 1">
                    </div>
                    <div class="content-box">
                        <img src="gambar/OMOTESANDO/WhatsApp Image 2025-03-03 at 18.14.15.jpeg" alt="Photo 3">
                    </div>
                    <div class="content-box">
                        <img src="gambar/KOORA/69e5c73f-0143-497f-9bc8-f47b3657c628.png" alt="Photo 2">
                    </div>
                    <div class="content-box">
                        <img src="gambar/POTTE/WhatsApp Image 2025-03-03 at 18.20.32 (2).jpeg" alt="Photo 4">
                    </div>
                </div>
                <button class="next-btn">&#10095;</button>
                </div>
        </section>

        <!-- Section 4 -->
        <section class="content-section">
            <h2>Favorites</h2>
            <div class="scroll-container">
                <button class="prev-btn">&#10094;</button>
                <div class="content">
                    <div class="content-box">
                        <img src="gambar/OMOTESANDO/WhatsApp Image 2025-03-03 at 18.14.15.jpeg" alt="Photo 3">
                    </div>
                    <div class="content-box">
                        <img src="gambar/KOORA/69e5c73f-0143-497f-9bc8-f47b3657c628.png" alt="Photo 2">
                    </div>
                    <div class="content-box">
                        <img src="gambar/POTTE/WhatsApp Image 2025-03-03 at 18.20.32 (2).jpeg" alt="Photo 4">
                    </div>
                    <div class="content-box">
                        <img src="gambar/ALLIGATOR/WhatsApp Image 2025-03-03 at 17.55.10 (1).jpeg" alt="Photo 1">
                    </div>
                </div>
                <button class="next-btn">&#10095;</button>
                </div>
        </section>
    </main>

    <script>
        function goToDetails(kafeId) {
    window.location.href = `recommendations.html?id=${kafeId}`;
}
    </script>
    <script src="js/script.js"></script> <!-- Script untuk scrolling -->
</body>
</html>
