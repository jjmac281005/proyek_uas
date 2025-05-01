<?php
session_start();
include 'database.php'; // koneksi database

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$username = $_POST['username'] ?? '';
$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$phone    = $_POST['phone'] ?? '';

// Validasi dasar
if ($username && $email && $phone) {
    if (!empty($password)) {
        // Jika password diubah, hash dulu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $username, $email, $hashed_password, $phone, $user_id);
    } else {
        // Jika password tidak diubah
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $phone, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Gagal memperbarui profil.";
    }

    $stmt->close();
} else {
    echo "<script>
        alert('Semua field kecuali password wajib diisi.');
        window.location.href = 'editprofile_customer.html';
    </script>";
}

$conn->close();
?>
