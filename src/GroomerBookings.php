<?php
session_start();
$isGroomerLoggedIn = isset($_SESSION['groomer_name']);
$groomerFullName = $isGroomerLoggedIn ? $_SESSION['groomer_name'] : '';

// Database connection
require_once 'db_config.php';

// Fetch Groomer's details based on the session user (assuming session contains groomer_id)
$groomer_id = $_SESSION['groomer_id']; // Assume session holds groomer_id
$sql = "SELECT * FROM groomers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $groomer_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the groomer's data
$groomerData = $result->fetch_assoc();
$stmt->close();

// Fetch the groomer's bookings if applicable (assuming there's another table for bookings)
$sql = "SELECT * FROM bookings WHERE groomer_id = ?"; // Example query for bookings
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $groomer_id);
$stmt->execute();
$bookingsResult = $stmt->get_result();

// Fetch the bookings
$bookings = [];
while ($row = $bookingsResult->fetch_assoc()) {
    $bookings[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Bookings | HappyTails</title>
  <style>
    /* Same styles as before */
    * {
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #f7f7f7;
    }

    .navbar {
      background-color: #fff;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid #ddd;
    }

    .navbar h1 {
      margin: 0;
      color: #1a1b4b;
      font-size: 24px;
    }

    .tabs {
      display: flex;
      justify-content: center;
      background-color: #fff;
      padding: 10px 0;
      border-bottom: 1px solid #ddd;
    }

    .tab {
      margin: 0 15px;
      padding: 10px 15px;
      cursor: pointer;
      border-bottom: 3px solid transparent;
      color: #555;
    }

    .tab.active {
      color: #1a1b4b;
      font-weight: bold;
      border-bottom: 3px solid #1a1b4b;
    }

    .container {
      max-width: 1100px;
      margin: 30px auto;
      background: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }

    table th, table td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }

    .empty-state {
      text-align: center;
      margin-top: 50px;
      color: #666;
    }

    .empty-state img {
      width: 80px;
      margin-bottom: 15px;
    }

    .call-support {
      position: absolute;
      top: 20px;
      right: 30px;
      color: #1a1b4b;
      font-weight: bold;
    }

  </style>
</head>
<body>

<div class="navbar">
  <h1>HappyTails</h1>

  <?php if ($isGroomerLoggedIn): ?>
    <div style="display: flex; align-items: center; gap: 20px;">
      <a href="Groomer_profile.php" style="text-decoration: none; color: #1a1b4b; font-weight: bold;">
        👤 <?php echo htmlspecialchars($groomerFullName); ?>
      </a>
      <form action="logout.php" method="POST" style="display: inline;">
        <button type="submit" style="color: red; background-color: transparent; border: 2px solid red; padding: 5px 10px; cursor: pointer;">
          Logout
        </button>
      </form>
    </div>
  <?php else: ?>
    <a href="GroomerLogin.php" style="text-decoration: none; color: #1a1b4b; font-weight: bold;">
      <i class="fas fa-sign-in-alt"></i> Login
    </a>
  <?php endif; ?>
</div>

<div class="tabs">
  <div class="tab active">Pending</div>
  <div class="tab">Accepted</div>
  <div class="tab">Ongoing</div>
  <div class="tab">Completed</div>
</div>

<div class="container">
  <h2>Your Bookings</h2>

  <!-- Bookings Table -->
  <table>
    <thead>
      <tr>
        <th>Booking ID</th>
        <th>Customer Name</th>
        <th>Customer Address</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Pet Details</th>
        <th>Message</th>
        <th>Total Price</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($bookings) > 0): ?>
        <?php foreach ($bookings as $booking): ?>
          <tr>
            <td><?php echo htmlspecialchars($booking['id']); ?></td>
            <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($booking['customer_address']); ?></td>
            <td><?php echo htmlspecialchars($booking['check_in']); ?></td>
            <td><?php echo htmlspecialchars($booking['check_out']); ?></td>
            <td><?php echo htmlspecialchars($booking['pet_details']); ?></td>
            <td><?php echo htmlspecialchars($booking['message']); ?></td>
            <td><?php echo htmlspecialchars($booking['total_price']); ?></td>
            <td>
              <button type="button" style="background-color: #1a1b4b; color: white; padding: 5px 10px; border: none;">View</button>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="9" class="empty-state">
            <img src="https://cdn-icons-png.flaticon.com/512/2748/2748558.png" alt="No Requests" />
            <div>You don't have any bookings</div>
            <small>As soon as a customer makes a booking, we will notify you</small>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
  // Tab switcher (optional interactivity)
  const tabs = document.querySelectorAll('.tab');
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      // Future: Use AJAX or PHP to load bookings per tab
    });
  });
</script>

</body>
</html>
