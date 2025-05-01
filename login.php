<?php
$conn = new mysqli("localhost", "root", "", "cafe_reservation");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        // Prepare query to check if the user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user is found
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, start session and set user data
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect to the appropriate dashboard based on user role
                if ($user['role'] === 'customer') {
                    header("Location: dashboard_customer.html");
                } else if ($user['role'] === 'owner') {
                    header("Location: dashboard_owner.html");
                }
                exit(); // Stop further script execution after redirect
            } else {
                echo "<script>alert('Incorrect password.'); window.location.href='login.html';</script>";
                exit();
            }
        } else {
            echo "<script>alert('No user found with that email.'); window.location.href='login.html';</script>";
            exit();
        }

        $stmt->close();
    }
}

$conn->close();
?>
