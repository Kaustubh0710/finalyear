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
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address_line1 = $_POST['address_line1'];
    $address_line2 = $_POST['address_line2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip_code'];
    $grooming_price = $_POST['grooming_price'];
    $service_type = $_POST['service_type'];
    $pet_type_serviced = $_POST['pet_type_serviced'];
    $pet_size_serviced = $_POST['pet_size_serviced'];
    $availability = $_POST['availability'];
    $years_experience = $_POST['years_experience'];
    $awards = $_POST['awards'];
    $special_skills = $_POST['special_skills'];

    $sql = "UPDATE groomers SET full_name=?, phone=?, address_line1=?, address_line2=?, city=?, state=?, zip_code=?, grooming_price=?, service_type=?, pet_type_serviced=?, pet_size_serviced=?, availability=?, years_experience=?, awards=?, special_skills=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssss", $full_name, $phone, $address_line1, $address_line2, $city, $state, $zip_code, $grooming_price, $service_type, $pet_type_serviced, $pet_size_serviced, $availability, $years_experience, $awards, $special_skills, $email);

    if ($stmt->execute()) {
        $success = "Profile updated successfully.";
    } else {
        $error = "Something went wrong.";
    }
}

// Fetch latest groomer data
$sql = "SELECT * FROM groomers WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$groomerData = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Groomer Profile - HappyTails</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            padding: 40px;
        }

        .container {
            max-width: 700px;
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
    <h2>Groomer Profile</h2>

    <?php if ($success) echo "<div class='success'>$success</div>"; ?>
    <?php if ($error) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($groomerData['full_name']); ?>" required>

        <label>Email (read-only)</label>
        <input type="email" value="<?php echo htmlspecialchars($groomerData['email']); ?>" readonly>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($groomerData['phone']); ?>" required>

        <label>Address Line 1</label>
        <input type="text" name="address_line1" value="<?php echo htmlspecialchars($groomerData['address_line1']); ?>" required>

        <label>Address Line 2</label>
        <input type="text" name="address_line2" value="<?php echo htmlspecialchars($groomerData['address_line2']); ?>">

        <label>City</label>
        <input type="text" name="city" value="<?php echo htmlspecialchars($groomerData['city']); ?>" required>

        <label>State</label>
        <input type="text" name="state" value="<?php echo htmlspecialchars($groomerData['state']); ?>" required>

        <label>Zip Code</label>
        <input type="text" name="zip_code" value="<?php echo htmlspecialchars($groomerData['zip_code']); ?>" required>

        <label>Grooming Price</label>
        <input type="number" name="grooming_price" value="<?php echo htmlspecialchars($groomerData['grooming_price']); ?>" required>

        <label>Service Type</label>
        <input type="text" name="service_type" value="<?php echo htmlspecialchars($groomerData['service_type']); ?>" required>

        <label>Pet Type Serviced</label>
        <input type="text" name="pet_type_serviced" value="<?php echo htmlspecialchars($groomerData['pet_type_serviced']); ?>" required>

        <label>Pet Size Serviced</label>
        <input type="text" name="pet_size_serviced" value="<?php echo htmlspecialchars($groomerData['pet_size_serviced']); ?>" required>

        <label>Availability</label>
        <textarea name="availability" rows="3" required><?php echo htmlspecialchars($groomerData['availability']); ?></textarea>

        <label>Years of Experience</label>
        <input type="number" name="years_experience" value="<?php echo htmlspecialchars($groomerData['years_experience']); ?>" required>

        <label>Awards</label>
        <textarea name="awards" rows="3"><?php echo htmlspecialchars($groomerData['awards']); ?></textarea>

        <label>Special Skills</label>
        <textarea name="special_skills" rows="3"><?php echo htmlspecialchars($groomerData['special_skills']); ?></textarea>

        <button type="submit">Update Profile</button>
    </form>

    <a href="GroomerBookings.php" class="back-link">← Back to Home</a>
</div>
</body>
</html>
