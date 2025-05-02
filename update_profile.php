<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$updates = [];
$params = [];
$types = "";

// Ambil input yang dikirim
$username = $_POST['username'] ?? '';
$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$phone    = $_POST['phone'] ?? '';

// Susun query dinamis berdasarkan field yang tidak kosong
if (!empty($username)) {
    $updates[] = "username = ?";
    $params[] = $username;
    $types .= "s";
}
if (!empty($email)) {
    $updates[] = "email = ?";
    $params[] = $email;
    $types .= "s";
}
if (!empty($phone)) {
    $updates[] = "phone = ?";
    $params[] = $phone;
    $types .= "s";
}
if (!empty($password)) {
    $updates[] = "password = ?";
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $params[] = $hashed_password;
    $types .= "s";
}

if (empty($updates)) {
    echo "Tidak ada data yang diubah.";
    exit();
}

$query = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
$params[] = $user_id;
$types .= "i";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header("Location: profile.php");
    exit();
} else {
    echo "Gagal update: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
