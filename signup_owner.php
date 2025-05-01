<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli("localhost", "root", "", "cafe_reservation");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone    = trim($_POST['phone'] ?? '');

    // Validasi input tidak kosong
    if ($username && $email && $password && $phone) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'owner';

        // Cek apakah email sudah terdaftar
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Email sudah digunakan.'); window.location.href='sign_up_owner.html';</script>";
        } else {
            // Insert pengguna baru
            $insert = $conn->prepare("INSERT INTO users (username, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
            $insert->bind_param("sssss", $username, $email, $hashed_password, $phone, $role);

            if ($insert->execute()) {
                // Set session untuk login otomatis
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                header("Location: dashboard_owner.html");
                exit();
            } else {
                echo "<script>alert('Terjadi kesalahan saat menyimpan data.'); window.location.href='sign_up_owner.html';</script>";
            }

            $insert->close();
        }

        $check->close();
    } else {
        echo "<script>alert('Semua field harus diisi.'); window.location.href='sign_up_owner.html';</script>";
    }

    $conn->close();
}
?>
