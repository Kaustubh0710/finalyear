<?php
session_start();

// Database config
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "happytails";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMsg = "";
$successMsg = "";

// Default stage
$stage = isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true ? "reset" : "otp";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['otp']) && isset($_SESSION['otp'])) {
        // OTP verification
        if ($_POST['otp'] === $_SESSION['otp']) {
            $_SESSION['otp_verified'] = true;
            $stage = "reset";
        } else {
            $errorMsg = "Invalid OTP. Please try again.";
        }
    } elseif (isset($_POST['new_password']) && isset($_POST['confirm_password']) && isset($_SESSION['otp_verified']) && $_SESSION['otp_verified']) {
        // Password reset
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $errorMsg = "Passwords do not match.";
            $stage = "reset";
        } elseif (strlen($new_password) < 6) {
            $errorMsg = "Password should be at least 6 characters.";
            $stage = "reset";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $email = $_SESSION['email'];

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                $successMsg = "Password reset successful. You can now <a href='login.php'>login</a>.";
                unset($_SESSION['otp']);
                unset($_SESSION['email']);
                unset($_SESSION['otp_verified']);
                $stage = "done";
            } else {
                $errorMsg = "Something went wrong. Please try again.";
                $stage = "reset";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
