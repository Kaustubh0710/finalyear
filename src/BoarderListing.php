<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "happytails";

$conn = new mysqli($host, $user, $pass, $db);

$location = $_GET['location'];

$sql = "SELECT name, dog_overnight, address, profile_picture, email FROM boarders WHERE address LIKE ?";
$stmt = $conn->prepare($sql);
$likeLocation = "%" . $location . "%";
$stmt->bind_param("s", $likeLocation);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Pet Boarders - HappyTails</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }

        .boarder-card {
            display: flex;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
            align-items: center;
        }

        .boarder-card img {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            object-fit: cover;
            margin-right: 20px;
        }

        .boarder-info {
            flex: 1;
        }

        .boarder-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .boarder-address {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .boarder-price {
            font-size: 16px;
            color: #e67e22;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div style="background: #fff3e0; padding: 15px; margin-bottom: 20px; border-radius: 10px;">
  <strong>Showing results for:</strong><br>
  📍 Location: <strong><?php echo htmlspecialchars($_GET['location']); ?></strong><br>
  🛏️ Stay Type: <strong>Overnight</strong> (Boarding)<br>
  📅 Dates: <strong><?php echo htmlspecialchars($_GET['checkin']); ?></strong> to <strong><?php echo htmlspecialchars($_GET['checkout']); ?></strong>
</div>

<h2>Available Pet Boarders</h2>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<a href="boarder_details.php?email=' . urlencode($row['email']) . '" style="text-decoration:none; color:inherit;">';
        echo '<div class="boarder-card">';
        echo '<img src="../' . htmlspecialchars($row['profile_picture']) . '" alt="Profile Picture">';
        echo '<div class="boarder-info">';
        echo '<div class="boarder-name">' . htmlspecialchars($row['name']) . '</div>';
        echo '<div class="boarder-address">' . htmlspecialchars($row['address']) . '</div>';
        echo '<div class="boarder-price">₹' . htmlspecialchars($row['dog_overnight']) . ' / night</div>';
        echo '</div></div>';
        echo '</a>';
    }
} else {
    echo "<p>No boarders available at the moment.</p>";
}
?>

</body>
</html>
