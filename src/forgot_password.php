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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$errorMsg = "";
$successMsg = "";
$stage = $_SESSION['stage'] ?? "email";

// === STEP 1: Enter Email ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Stage 1: Email input
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Email exists
            $_SESSION['email'] = $email;
            $otp = strval(rand(100000, 999999));
            $_SESSION['otp'] = $otp;
            $_SESSION['stage'] = "otp";

            // Send OTP using PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'kaustubhbastawadi@gmail.com'; // your Gmail
                $mail->Password = 'lfuhzkkcgsnxbzud'; // your app password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('kaustubhbastawadi@gmail.com', 'HappyTails');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Password Reset';
                $mail->Body = "<p>Your OTP is: <strong>$otp</strong></p>";

                $mail->send();
                $successMsg = "OTP sent to your email.";
                $stage = "otp";
            } catch (Exception $e) {
                $errorMsg = "Failed to send OTP. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $errorMsg = "Email not found.";
        }
        $stmt->close();
    }

    // Stage 2: OTP verification
    elseif (isset($_POST['otp']) && isset($_SESSION['otp'])) {
        if ($_POST['otp'] === $_SESSION['otp']) {
            $_SESSION['otp_verified'] = true;
            $_SESSION['stage'] = "reset";
            $stage = "reset";
        } else {
            $errorMsg = "Invalid OTP. Try again.";
            $stage = "otp";
        }
    }

    // Stage 3: Reset password
    elseif (isset($_POST['new_password'], $_POST['confirm_password']) && $_SESSION['otp_verified']) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $errorMsg = "Passwords do not match.";
            $stage = "reset";
        } elseif (strlen($new_password) < 6) {
            $errorMsg = "Password must be at least 6 characters.";
            $stage = "reset";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $email = $_SESSION['email'];

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                $successMsg = "Password successfully reset. <a href='login.php'>Login</a>";
                session_unset();
                session_destroy();
                $stage = "done";
            } else {
                $errorMsg = "Error updating password.";
                $stage = "reset";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        /* Same CSS you used is good, feel free to add margin-top for each stage */
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>

        <?php if (!empty($errorMsg)): ?>
            <div class="msg error"><?php echo $errorMsg; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMsg)): ?>
            <div class="msg success"><?php echo $successMsg; ?></div>
        <?php endif; ?>

        <?php if ($stage === "email"): ?>
            <form method="post">
                <label for="email">Enter your Email</label>
                <input type="email" name="email" required>
                <button type="submit">Send OTP</button>
            </form>
        <?php elseif ($stage === "otp"): ?>
            <form method="post">
                <label for="otp">Enter OTP</label>
                <input type="text" name="otp" maxlength="6" required>
                <button type="submit">Verify OTP</button>
            </form>
        <?php elseif ($stage === "reset"): ?>
            <form method="post">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" required>
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" required>
                <button type="submit">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
