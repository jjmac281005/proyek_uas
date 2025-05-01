<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone    = $_POST['phone'] ?? '';

    if ($username && $email && $password && $phone) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'customer';

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $hashed_password, $phone, $role);

        if ($stmt->execute()) {
            header("Location: login.html");
            exit();
        } else {
            echo "<script>alert('Email sudah digunakan atau terjadi kesalahan.');</script>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
