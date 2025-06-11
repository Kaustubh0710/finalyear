<?php
include 'auth_check.php';

// DB connection
$conn = new mysqli("localhost", "root", "", "happytails");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize inputs
$email = $_GET['email'] ?? '';
$breed = $_GET['breed'] ?? '';
$name = $_GET['name'] ?? '';

// Fetch pet data
$pet_sql = "SELECT * FROM matingregister WHERE email = ? AND breed = ? AND pet_name = ?";
$stmt = $conn->prepare($pet_sql);
$stmt->bind_param("sss", $email, $breed, $name);
$stmt->execute();
$pet_result = $stmt->get_result();

if ($pet_result->num_rows === 1) {
    $pet = $pet_result->fetch_assoc();

    // Fetch owner info
    $owner_sql = "SELECT full_name, phone FROM users WHERE email = ?";
    $stmt2 = $conn->prepare($owner_sql);
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $owner_result = $stmt2->get_result();
    $owner = $owner_result->fetch_assoc();
} else {
    echo "Pet not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pet['pet_name']); ?> - Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f6f6;
            padding: 2rem;
        }
        .profile-card {
            max-width: 700px;
            margin: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        h2 {
            text-align: center;
            margin-bottom: 1rem;
            color: #6c5ce7;
        }
        .section {
            margin-bottom: 1.5rem;
        }
        .section h3 {
            color: #333;
            margin-bottom: 0.5rem;
            border-bottom: 2px solid #6c5ce7;
            display: inline-block;
        }
        .section ul {
            list-style: none;
            padding: 0;
        }
        .section li {
            padding: 0.4rem 0;
        }
    </style>
</head>
<body>

<div class="profile-card" style="display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">

    <!-- Pet Image -->
    <div style="flex: 1 1 250px; max-width: 250px;">
        <img src="uploads/<?php echo htmlspecialchars($pet['pet_image']); ?>" alt="Pet Image" style="width:100%; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">
    </div>

    <!-- Pet and Owner Info -->
    <div style="flex: 2 1 400px;">
        <h2 style="text-align: center; color: #6c5ce7;"><?php echo htmlspecialchars($pet['pet_name']); ?>'s Profile</h2>

        <div class="section">
            <h3>Facts About Me</h3>
            <ul>
                <li><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></li>
                <li><strong>Gender:</strong> <?php echo htmlspecialchars($pet['gender']); ?></li>
                <li><strong>Vaccinated:</strong> <?php echo htmlspecialchars($pet['vaccination_status']); ?></li>
                <li><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> years</li>
                <li><strong>Weight:</strong> <?php echo htmlspecialchars($pet['weight']); ?> kg</li>
                <li><strong>Sociable:</strong> <?php echo htmlspecialchars($pet['sociable']); ?></li>
                <li><strong>Aggressive Level:</strong> <?php echo htmlspecialchars($pet['aggressive_level']); ?></li>
            </ul>
        </div>

        <div class="section">
            <h3>Owner Info</h3>
            <ul>
                <li><strong>Name:</strong> <?php echo htmlspecialchars($owner['full_name']); ?></li>
                <li><strong>Phone:</strong> <?php echo htmlspecialchars($owner['phone']); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></li>
            </ul>
        </div>

        <div style="text-align:center;">
            <a href="dogmating.php" style="padding:10px 20px;background:#6c5ce7;color:white;text-decoration:none;border-radius:8px;">← Back to List</a>
        </div>
    </div>
</div>

</body>
</html>
<?php
$conn->close();
?>
