<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "happytails";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data safely
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Simple password match check
if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Check if user already exists by email
$check_sql = "SELECT id FROM users WHERE email = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    // User exists - show message and stop
    echo "User already exists with this email.";
    $check_stmt->close();
    $conn->close();
    exit;
}
$check_stmt->close();

// Hash the password before storing
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$sql = "INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $full_name, $email, $phone, $hashed_password);

if ($stmt->execute()) {
    // Redirect to login/index page
    header("Location: login.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
