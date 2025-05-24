<?php
session_start();

// DB connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "education_connect";
$conn = new mysqli($host, $user, $pass, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check session
if (!isset($_SESSION['email'])) {
    die("Unauthorized access.");
}

$email = $_SESSION['email'];

$successMsg = "";
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validate passwords
    if (strlen($newPassword) < 6) {
        $errorMsg = "Password must be at least 6 characters.";
    } elseif ($newPassword !== $confirmPassword) {
        $errorMsg = "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE staff SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            $successMsg = "Password updated successfully! Redirecting to login...";
            session_destroy();
            header("refresh:3;url=staff_login.php"); // Redirect after 3 seconds
        } else {
            $errorMsg = "Error updating password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>NTTF | Reset Password</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f0f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 35px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #003366;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #003366;
            color: #fff;
            border: none;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #00509e;
        }

        .msg {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .success {
            background: #e0f5e0;
            color: #2e7d32;
        }

        .error {
            background: #ffe5e5;
            color: #d32f2f;
        }

        .note {
            font-size: 0.85em;
            color: #666;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Your Password</h2>

        <?php if (!empty($errorMsg)): ?>
            <div class="msg error"><?php echo $errorMsg; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMsg)): ?>
            <div class="msg success"><?php echo $successMsg; ?></div>
        <?php endif; ?>

        <?php if (empty($successMsg)): ?>
        <form method="post">
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <div class="note">* Password should be at least 6 characters. Use a mix of letters, numbers, and symbols.</div>

            <button type="submit">Update Password</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
