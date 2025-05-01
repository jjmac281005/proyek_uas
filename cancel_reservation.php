<?php
session_start();
include 'database.php';

// Cek apakah id dan user_id ada di POST dan SESSION
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("UPDATE reservation SET status = 'Canceled' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: dashboard_main.html");
        exit();
    } else {
        echo "No rows updated. Check if the reservation exists or is owned by the current user.";
    }
    $stmt->close();
} else {
    echo "Invalid request. Reservation ID or user ID not set.";
}

$conn->close();
?>