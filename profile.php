<?php
// Mulai session untuk mengakses data pengguna
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Jika tidak login, arahkan ke halaman login
    exit();
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "cafe_reservation");

// Cek koneksi database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Query untuk mengambil data pengguna berdasarkan user_id
$stmt = $conn->prepare("SELECT username, email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id); // Bind parameter dengan tipe data integer
$stmt->execute();

// Ambil hasil query
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Ambil data pengguna sebagai array asosiasi

// Tutup statement dan koneksi
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <a href="dashboard_main.php"><img src="gambar/logo_text.png" alt="Logo Text"></a>
        </div>
        <div class="nav-buttons">
            <button class="nav-btn"><a href="search.html"><img src="gambar/icons8-search-50.png" width="30" height="30"></a></button>
            <button class="nav-btn"><a href="notif.php"><img src="gambar/icons8-notifications-64.png" width="33" height="33"></a></button>
            <button class="nav-btn"><a href="profile.php"><img src="gambar/icons8-male-user-48.png" width="30" height="30"></a></button>
        </div>
    </header>

    <main class="profile-container">
        <div class="profile-card">
            <!-- Gambar profil -->
            <img src="gambar/christopher-campbell-rDEOVtE7vOs-unsplash.jpg" alt="User Photo" class="profile-pic">
            
            <!-- Menampilkan data pengguna -->
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($user['phone']); ?></p>

            <!-- Tombol untuk mengedit profil dan logout -->
            <div class="profile-buttons">
                <button class="edit-btn" onclick="window.location.href='editprofile_customer.html'">Edit Profile</button>
                <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
        </div>
    </main>
</body>
</html>
