<?php
session_start();
$conn = new mysqli("localhost", "root", "", "cafedb");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil nama kafe berdasarkan user_id
$stmt = $conn->prepare("
    SELECT admin.username 
    FROM admin 
    JOIN cafes ON cafes.id = admin.cafe_id 
    WHERE admin.id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$currentCafeName = $row['username'];
$stmt->close();

// Ubah spasi jadi underscore
$cafeFolder = "gambar/" . str_replace(' ', '_', strtolower($currentCafeName)) . "/";
if (!is_dir($cafeFolder)) {
    mkdir($cafeFolder, 0777, true);
}

// Inisialisasi array untuk query update
$updates = [];
$params = [];
$types = "";

// Ambil input
$name = $_POST['name'] ?? '';
$address = $_POST['address'] ?? '';
$phone = $_POST['phone'] ?? '';
$weekdays_hours = $_POST['weekdays_hours'] ?? '';
$weekend_hours = $_POST['weekend_hours'] ?? '';

// Tambahkan input yang tidak kosong ke query
if (!empty($name)) {
    $updates[] = "cafes.name = ?";
    $params[] = $name;
    $types .= "s";
}
if (!empty($address)) {
    $updates[] = "cafes.address = ?";
    $params[] = $address;
    $types .= "s";
}
if (!empty($phone)) {
    $updates[] = "admin.phone = ?";
    $params[] = $phone;
    $types .= "s";
}
if (!empty($weekdays_hours)) {
    $updates[] = "admin.weekdays_hours = ?";
    $params[] = $weekdays_hours;
    $types .= "s";
}
if (!empty($weekend_hours)) {
    $updates[] = "admin.weekend_hours = ?";
    $params[] = $weekend_hours;
    $types .= "s";
}

// Proses upload logo
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $filename = basename($_FILES['logo']['name']);
    $targetPath = $cafeFolder . $filename;

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
        $updates[] = "cafes.logo = ?";
        $params[] = $targetPath; // Simpan relative path
        $types .= "s";
    } else {
        echo "Gagal mengupload logo.";
        exit();
    }
}

// Jika tidak ada data yang diubah
if (empty($updates)) {
    echo "Tidak ada data yang diubah.";
    exit();
}

// Bangun query dan jalankan update
$query = "
    UPDATE admin 
    JOIN cafes ON cafes.id = admin.cafe_id 
    SET " . implode(', ', $updates) . " 
    WHERE admin.id = ?
";
$params[] = $user_id;
$types .= "i";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header("Location: profile_owner.php");
    exit();
} else {
    echo "Gagal update: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
