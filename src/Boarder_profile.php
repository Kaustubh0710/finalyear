<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "happytails";

$conn = new mysqli($host, $user, $pass, $db);

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ServiceProvLogin.php");
    exit();
}

$email = $_SESSION['email'];
$success = "";
$error = "";

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $certificates = $_POST['certificates'];

    $sql = "UPDATE boarders SET name=?, contact=?, address=?, certificates=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $contact, $address, $certificates, $email);

    if ($stmt->execute()) {
        $success = "Profile updated successfully.";
    } else {
        $error = "Something went wrong.";
    }
}

// Fetch latest boarder data
$sql = "SELECT * FROM boarders WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Boarder Profile - HappyTails</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background: #ffb74d;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .success {
            color: green;
            margin-top: 10px;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        a.back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #444;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Boarder Profile</h2>

    <?php if ($success) echo "<div class='success'>$success</div>"; ?>
    <?php if ($error) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" required>

        <label>Email (read-only)</label>
        <input type="email" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly>

        <label>Contact</label>
        <input type="text" name="contact" value="<?php echo htmlspecialchars($userData['contact']); ?>" required>

        <label>Address</label>
        <textarea name="address" rows="3" required><?php echo htmlspecialchars($userData['address']); ?></textarea>

        <label>Certificates</label>
        <textarea name="certificates" rows="3" required><?php echo htmlspecialchars($userData['certificates']); ?></textarea>

        <button type="submit">Update Profile</button>
    </form>

    <a href="BoardBookings.php" class="back-link">← Back to Home</a>
</div>
</body>
</html>
