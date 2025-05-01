<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        // Cari user berdasarkan email dan role owner
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'owner'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Simpan data user dalam session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header("Location: dashboard_owner.html");
                exit();
            } else {
                echo "<script>alert('Password salah.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Akun owner tidak ditemukan.'); window.history.back();</script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('Email dan password wajib diisi.'); window.history.back();</script>";
    }
}
?>
