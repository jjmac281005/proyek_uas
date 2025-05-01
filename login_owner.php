<?php
session_start();
$conn = new mysqli("localhost", "root", "", "cafe_reservation");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'owner'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: dashboard_owner.html");
                exit();
            } else {
                echo "<script>alert('Incorrect password.'); window.location.href='login_owner.html';</script>";
            }
        } else {
            echo "<script>alert('No owner account found with that email.'); window.location.href='login_owner.html';</script>";
        }

        $stmt->close();
    }
}

$conn->close();
?>
