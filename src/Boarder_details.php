<?php
include 'auth_check.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "happytails";
$conn = new mysqli($host, $user, $pass, $db);

if (!isset($_GET['email'])) {
    echo "No boarder selected.";
    exit;
}

$email = $_GET['email'];

$sql = "SELECT * FROM boarders WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$boarder = $result->fetch_assoc();

if (!$boarder) {
    echo "Boarder not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title><?php echo htmlspecialchars($boarder['name']); ?> - Boarder Details</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f9f9f9;
      color: #333;
      padding: 40px 20px;
    }

    .profile-container {
      max-width: 800px;
      margin: auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .header {
      background: #ff8c42;
      color: white;
      padding: 25px;
      text-align: center;
    }

    .header h2 {
      margin: 0;
    }

    .profile-body {
      display: flex;
      flex-wrap: wrap;
      padding: 25px;
      gap: 30px;
    }

    .profile-image {
      flex: 1 1 150px;
      max-width: 180px;
    }

    .profile-image img {
      width: 100%;
      height: auto;
      border-radius: 12px;
      object-fit: cover;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .details {
      flex: 2 1 300px;
    }

    .details h3 {
      margin-top: 0;
      margin-bottom: 15px;
      font-size: 22px;
      color: #444;
    }

    .detail-item {
      margin-bottom: 10px;
      line-height: 1.5;
    }

    .services {
      background: #f7f7f7;
      padding: 20px;
      border-top: 1px solid #eee;
    }

    .services h3 {
      margin-top: 0;
      margin-bottom: 15px;
      font-size: 20px;
    }

    .services ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .services li {
        margin-bottom: 10px;
        padding-left: 25px; /* Increased to make space for icon */
        position: relative;
    }

    .services li::before {
        content: "✔️";
        position: absolute;
        left: 0;
        top: 0;
        /* Optional: Add a bit of vertical alignment tweak */
        line-height: 1;
    }

    @media (max-width: 600px) {
      .profile-body {
        flex-direction: column;
        align-items: center;
      }

      .details {
        text-align: center;
      }

      .services li {
        text-align: center;
      }
    }
  </style>
</head>
<body>

<div class="profile-container">
  <div class="header">
    <h2><?php echo htmlspecialchars($boarder['name']); ?></h2>
  </div>

  <div class="profile-body">
    <div class="profile-image">
      <img src="../<?php echo htmlspecialchars($boarder['profile_picture']); ?>" alt="Profile Picture">
    </div>
    <div class="details">
      <h3>Contact Info</h3>
      <div class="detail-item"><strong>📞 Phone:</strong> <?php echo htmlspecialchars($boarder['contact']); ?></div>
      <div class="detail-item"><strong>📧 Email:</strong> <?php echo htmlspecialchars($boarder['email']); ?></div>
      <div class="detail-item"><strong>📍 Address:</strong> 
        <?php echo htmlspecialchars($boarder['address']) . ', ' . htmlspecialchars($boarder['city']) . ', ' . htmlspecialchars($boarder['state']) . ' - ' . htmlspecialchars($boarder['pin_code']); ?>
      </div>
    </div>
  </div>

  <div class="services">
    <h3>Pet Boarding Services & Charges</h3>
    <ul>
      <li>Dog Day Care: ₹<?php echo $boarder['dog_daycare']; ?> / hour</li>
      <li>Dog Overnight: ₹<?php echo $boarder['dog_overnight']; ?> / night</li>
      <li>Cat Day Care: ₹<?php echo $boarder['cat_daycare']; ?> / hour</li>
      <li>Cat Overnight: ₹<?php echo $boarder['cat_overnight']; ?> / night</li>
    </ul>
  </div>
</div>

</body>
</html>
