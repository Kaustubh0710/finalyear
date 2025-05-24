<?php
session_start();
$isSPLoggedIn = isset($_SESSION['name']);
$spFullName = $isSPLoggedIn ? $_SESSION['name'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Bookings | HappyTails</title>
  <style>
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

  <?php if ($isSPLoggedIn): ?>
    <div style="display: flex; align-items: center; gap: 20px;">
      <a href="Boarder_profile.php" style="text-decoration: none; color: #1a1b4b; font-weight: bold;">
        👤 <?php echo htmlspecialchars($spFullName); ?>
      </a>
      <form action="logout.php" method="POST" style="display: inline;">
        <button type="submit" style="color: red; background-color: transparent; border: 2px solid red; padding: 5px 10px; cursor: pointer;">
          Logout
        </button>
      </form>
    </div>
  <?php else: ?>
    <a href="ServiceProvLogin.php" style="text-decoration: none; color: #1a1b4b; font-weight: bold;">
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
        <th>UUID</th>
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
      <!-- This part should be dynamically generated using PHP -->
      <tr>
        <td colspan="9" class="empty-state">
          <img src="https://cdn-icons-png.flaticon.com/512/2748/2748558.png" alt="No Requests" />
          <div>You don't have any pending request</div>
          <small>As soon as a customer makes a booking, we will notify you</small>
        </td>
      </tr>
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
