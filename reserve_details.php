<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil reservasi terbaru milik user
$stmt = $conn->prepare("
    SELECT reservation.id, users.username, seat_number, time_from, time_to, date_reservation
    FROM reservation
    JOIN users
    ON users.id = reservation.user_id
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('No reservations found.'); window.location.href = 'dashboard_main.html';</script>";
    exit();
}

$reservation = $result->fetch_assoc();
$reservation_id = $reservation['id'];

// Gabungkan tanggal dan jam selesai reservasi untuk validasi status
$reservation_end = new DateTime($reservation['date_reservation'] . ' ' . $reservation['time_to']);
$current_time = new DateTime();
$status = ($reservation_end < $current_time) ? 'Expired' : 'Ongoing';

// Format tampilan waktu reservasi
$reservation_time_display = date('j M Y', strtotime($reservation['date_reservation'])) . ', ' .
    substr($reservation['time_from'], 0, 5) . ' - ' . substr($reservation['time_to'], 0, 5);

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reservation Details</title>
  <link rel="stylesheet" href="reserve_details.css">
  <style>
    .logo-kafe {
      text-align: center;
      margin-bottom: 10px;
    }
    .logo-kafe img {
      width: 250px;
      height: 250px;
      object-fit: cover;
      border-radius: 5%;
    }
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
    <div class="logo">
      <a href="dashboard_main.html"><img src="gambar/logo_text.png" alt="text Logo"></a>
    </div>
    <div class="nav-buttons">
      <button class="nav-btn"><a href="search.html"><img src="gambar/icons8-search-50.png" width="30" height="30"></a></button>
      <button class="nav-btn"><a href="notif.html"><img src="gambar/icons8-notifications-64.png" width="33" height="33"></a></button>
      <button class="nav-btn"><a href="profile.php"><img src="gambar/icons8-male-user-48.png" width="30" height="30"></a></button>
    </div>
  </header>

  <div class="container mt-4">
    <div class="row">
      <div class="col-md-3">
        <div class="logo-kafe">
          <img src="gambar/ALLIGATOR/LOGO.jpeg" alt="Cafe Logo">
        </div>
      </div>
    </div>

    <div class="col-md-8 justify-contend-end">
      <div class="info-box"><?= htmlspecialchars($reservation['username']) ?></div>
      <div class="info-box"><?= htmlspecialchars($reservation_time_display) ?></div>
      <div class="info-box">Seat <?= htmlspecialchars($reservation['seat_number']) ?></div>
      <div class="info-box" id="reservationStatus"></div>
    </div>
  </div>

  <div class="cancel-container">
    <button class="btn btn-cancel" onclick="showCancelPopup()">Cancel</button>
  </div>

  <div class="back-container">
    <button class="btn btn-back" onclick="goToDetails()">Back to Dashboard</button>
  </div>

  <!-- Cancel popup -->
  <form method="POST" action="cancel_reservation.php">
    <input type="hidden" name="id" value="<?= $reservation_id ?>">
    <div id="cancelPopup" class="popup-overlay">
      <div class="popup-box">
        <p>Are you sure you want to cancel this reservation?</p>
        <button type="submit">Yes</button>
        <button type="button" onclick="closeCancelPopup()">No</button>
      </div>
    </div>
  </form>

  <script>
    function goToDetails() {
      window.location.href = 'dashboard_main.html';
    }

    function showCancelPopup() {
      document.getElementById('cancelPopup').style.display = 'flex';
    }

    function closeCancelPopup() {
      document.getElementById('cancelPopup').style.display = 'none';
    }

    document.addEventListener("DOMContentLoaded", function() {
    const reservationEnd = new Date("<?= $reservation_end->format('Y-m-d H:i:s') ?>");
    const currentTime = new Date();
    const statusElement = document.getElementById('reservationStatus');
    
    if (reservationEnd < currentTime) {
        statusElement.innerHTML = 'Status: Expired';
    } else {
        statusElement.innerHTML = 'Status: Ongoing';
    }
    });
  </script>
</body>
</html>
