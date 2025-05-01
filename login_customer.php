<?php
session_start();
$conn = new mysqli("localhost", "root", "", "cafe_reservation");

// Aktifkan error report (bisa dimatikan saat production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'customer'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user found
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Set session and redirect
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: dashboard_main.html");
                exit();
            } else {
                echo "<script>alert('Incorrect password.'); window.location.href='login.html';</script>";
                exit();
            }
        } else {
            echo "<script>alert('No customer found with that email.'); window.location.href='login.html';</script>";
            exit();
        }

        $stmt->close();
    }
}

$conn->close();
?>
