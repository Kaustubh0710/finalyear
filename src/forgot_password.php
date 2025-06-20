<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$stage = $_SESSION['stage'] ?? 'email';
$errorMsg = '';
$successMsg = '';

// Success message handler after final step
if (isset($_GET['done']) && isset($_SESSION['success_msg'])) {
    $successMsg = $_SESSION['success_msg'];
    session_unset();
    session_destroy();
}

// Dummy user data
$users = [
    ['email' => 'kaustubhbastawadi@gmail.com', 'password' => password_hash('oldpassword', PASSWORD_DEFAULT)]
];

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kaustubhbastawadi@gmail.com'; // Your email
        $mail->Password   = 'your-app-password';           // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('kaustubhbastawadi@gmail.com', 'Password Reset');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Password Reset';
        $mail->Body    = "Your OTP is: <b>$otp</b>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_email'])) {
        $email = $_POST['email'];
        $userExists = false;
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $userExists = true;
                break;
            }
        }

        if ($userExists) {
            $otp = rand(100000, 999999);
            $_SESSION['email'] = $email;
            $_SESSION['otp'] = $otp;
            $_SESSION['stage'] = 'otp';

            if (!sendOTP($email, $otp)) {
                $errorMsg = "Failed to send OTP. Please try again.";
                $_SESSION['stage'] = 'email';
            }
        } else {
            $errorMsg = "Email not registered.";
        }
    }

    if (isset($_POST['submit_otp'])) {
        $enteredOtp = $_POST['otp'];
        if ($enteredOtp == $_SESSION['otp']) {
            $_SESSION['stage'] = 'reset';
        } else {
            $errorMsg = "Invalid OTP!";
        }
    }

    if (isset($_POST['submit_password'])) {
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];
        if ($password === $confirm) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Simulate DB update
            foreach ($users as &$user) {
                if ($user['email'] === $_SESSION['email']) {
                    $user['password'] = $hashedPassword;
                    break;
                }
            }

            $_SESSION['success_msg'] = "Password successfully reset. <a href='login.php'>Login</a>";
            header("Location: reset_password.php?done=1");
            exit;
        } else {
            $errorMsg = "Passwords do not match.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f4f4f4; }
        .container { max-width: 400px; margin: auto; background: white; padding: 20px; box-shadow: 0 0 10px gray; border-radius: 10px; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        .btn { background-color: #28a745; color: white; border: none; padding: 10px; cursor: pointer; width: 100%; }
        .btn:hover { background-color: #218838; }
        .error { color: red; margin: 10px 0; }
        .success { color: green; margin: 10px 0; }
    </style>
</head>
<body>
<div class="container">
    <h2>Reset Password</h2>

    <?php if ($errorMsg): ?>
        <div class="error"><?= $errorMsg ?></div>
    <?php endif; ?>

    <?php if ($successMsg): ?>
        <div class="success"><?= $successMsg ?></div>
    <?php endif; ?>

    <?php if ($stage === 'email'): ?>
        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" required>
            <button class="btn" type="submit" name="submit_email">Send OTP</button>
        </form>
    <?php elseif ($stage === 'otp'): ?>
        <form method="POST">
            <label>Enter OTP</label>
            <input type="number" name="otp" required>
            <button class="btn" type="submit" name="submit_otp">Verify OTP</button>
        </form>
    <?php elseif ($stage === 'reset'): ?>
        <form method="POST">
            <label>New Password</label>
            <input type="password" name="password" required>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
            <button class="btn" type="submit" name="submit_password">Reset Password</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
<?php
// End of the PHP script
