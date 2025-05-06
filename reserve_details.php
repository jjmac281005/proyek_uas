<?php
session_start();
include 'database.php'; // $conn_main
include 'cafedb.php';   // $conn_cafe

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
date_default_timezone_set('Asia/Jakarta');
$current_time = new DateTime();

// Ambil semua reservasi user yang belum di-cancel dari database utama
$stmt = $conn->prepare("
    SELECT r.id, r.cafe_name, u.username, r.seat_number, 
           r.time_from, r.time_to, r.date_reservation
    FROM reservation AS r
    JOIN users AS u ON u.id = r.user_id
    WHERE r.user_id = ? AND r.status != 'Canceled'
    ORDER BY r.date_reservation DESC, r.time_from DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$id_user = $user_id;
$pesan = "Reservasi berhasil ditambahkan.";
$stmt_notif = $conn->prepare("INSERT INTO tb_notifikasi (pesan, id_user) VALUES (?, ?)");
$stmt_notif->bind_param("si", $pesan, $id_user);
$stmt_notif->execute();
$stmt_notif->close();


$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservation_end = new DateTime($row['date_reservation'] . ' ' . $row['time_to']);
    $row['status'] = ($reservation_end > $current_time) ? 'Ongoing' : 'Expired';
    $row['reservation_time_display'] =
        date('j M Y', strtotime($row['date_reservation'])) . ', ' .
        substr($row['time_from'], 0, 5) . ' - ' .
        substr($row['time_to'], 0, 5);

    // Ambil logo dari database cafedb
    $stmt_logo = $conn_cafe->prepare("SELECT logo FROM cafes WHERE name = ?");
    $stmt_logo->bind_param("s", $row['cafe_name']);
    $stmt_logo->execute();
    $logo_result = $stmt_logo->get_result();
    if ($logo_row = $logo_result->fetch_assoc()) {
        $row['logo'] = $logo_row['logo'];
    } else {
        $row['logo'] = 'gambar/default_logo.jpg'; // fallback
    }
    $stmt_logo->close();

    $reservations[] = $row;
}

$stmt->close();
$conn->close();
$conn_cafe->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reservation Details</title>
  <link rel="stylesheet" href="reserve_details.css">
  <style>
    .reservation-card {
      background-color: #c49a6c;
      padding: 15px;
      max-width: 1286px;
      margin-bottom: 10px;
      margin-left: 100px;
      margin-top: 10px;
      border-radius: 10px;
    }
    .logo-kafe {
      text-align: center;
      float: right;
      margin-bottom: 10px;
    }
    .logo-kafe img {
      width: 250px;
      height: 250px;
      object-fit: cover;
      border-radius: 5%;
    }
    .status-ongoing { color: green; font-weight: bold; }
    .status-expired { color: red; font-weight: bold; }
    .popup-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 999;
    }
    .popup-box {
      background-color: #203b58;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
    }
    .popup-box button {
      padding: 10px 20px;
      margin: 10px;
      background-color: #2c5469;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      width: 100px;
      font-size: 16px;
      font-weight: bold;
    }
    .popup-box button:hover {
      background-color: #c49a6c;
      color: #203b58;
    }
  </style>
</head>
<body>
<header class="navbar">
  <div class="logo"><a href="dashboard_main.php"><img src="gambar/logo_text.png" alt="text Logo"></a></div>
  <div class="nav-buttons">
    <button class="nav-btn"><a href="notif_owner.php"><img src="gambar/icons8-notifications-64.png" width="33" height="33"></a></button>
    <button class="nav-btn"><a href="profile_owner.php"><img src="gambar/icons8-male-user-48.png" width="30" height="30"></a></button>
  </div>
</header>

<div class="container mt-4">
  <?php if (empty($reservations)): ?>
    <p>No reservations found.</p>
  <?php else: ?>
    <?php foreach ($reservations as $index => $res): ?>
      <div class="reservation-card">
        <div class="row">
          <div class="col-md-3">
            <div class="logo-kafe">
              <img src="<?= htmlspecialchars($res['logo']) ?>" alt="Cafe Logo">
            </div>
          </div>
          <div class="col-md-9">
            <div class="info-box"><?= htmlspecialchars($res['username']) ?></div>            
            <div class="info-box"><?= htmlspecialchars($res['cafe_name']) ?></div>
            <div class="info-box"><?= htmlspecialchars($res['reservation_time_display']) ?></div>
            <div class="info-box">Seat <?= htmlspecialchars($res['seat_number']) ?></div>
            <div class="info-box" id="reservationStatus">
              <p>Status:
                <span class="status-<?= strtolower($res['status']) ?>">
                  <?= $res['status'] ?>
                </span>
              </p>

              <?php if ($res['status'] === 'Ongoing'): ?>
                <div class="cancel-container">
                  <button class="btn btn-cancel" onclick="showCancelPopup(<?= $index ?>)">Cancel</button>
                </div>
                <form method="POST" action="cancel_reservation.php">
                  <input type="hidden" name="id" value="<?= $res['id'] ?>">
                  <div id="cancelPopup<?= $index ?>" class="popup-overlay">
                    <div class="popup-box">
                      <p>Are you sure you want to cancel this reservation?</p>
                      <button type="submit">Yes</button>
                      <button type="button" onclick="closeCancelPopup(<?= $index ?>)">No</button>
                    </div>
                  </div>
                </form>
              <?php else: ?>
                <p><em>Cannot cancel expired reservation</em></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<div class="back-container">
  <button class="btn btn-back" onclick="window.location.href='dashboard_main.php'">Back to Dashboard</button>
</div>

<script>
  function showCancelPopup(index) {
    document.getElementById('cancelPopup' + index).style.display = 'flex';
  }
  function closeCancelPopup(index) {
    document.getElementById('cancelPopup' + index).style.display = 'none';
  }
</script>
</body>
</html>
