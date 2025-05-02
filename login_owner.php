<?php
session_start();
$conn = new mysqli("localhost", "root", "", "cafedb");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? AND role = 'owner'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($password == $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: dashboard_owner.php");
                exit();
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "No owner account found with that email.";
        }

        $stmt->close();
    }
}

$conn->close();
?>
