<?php
$conn = new mysqli("localhost", "root", "", "cafe_reservation");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone    = $_POST['phone'] ?? '';

    if ($username && $email && $password && $phone) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'customer';

        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email sudah digunakan.";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $hashed_password, $phone, $role);

            if ($stmt->execute()) {
                echo "success"; // Kirimkan 'success' jika berhasil
            } else {
                echo "Terjadi kesalahan saat mendaftar.";
            }
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Semua field harus diisi.";
    }
}
?>
