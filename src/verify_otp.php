<?php
session_start();

$success = false;
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = trim($_POST['otp']);

    if (isset($_SESSION['otp']) && $enteredOtp === $_SESSION['otp']) {
        unset($_SESSION['otp']); // Clear OTP after successful use
        $success = true;

        // Optional: Show success message briefly before redirecting
        header("refresh:3;url=reset_password.php");
    } else {
        $errorMsg = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP | NTTF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #e6f0ff, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: #fff;
            padding: 35px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .card h2 {
            margin-bottom: 25px;
            color: #003366;
        }

        .card img {
            width: 60px;
            margin-bottom: 15px;
        }

        .input-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            letter-spacing: 4px;
            text-align: center;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #00509e;
        }

        .msg {
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 6px;
        }

        .error {
            background-color: #ffe0e0;
            color: #d32f2f;
        }

        .success {
            background-color: #e0ffe3;
            color: #2e7d32;
        }

        .note {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 12px;
        }

        .timer {
            color: #888;
            font-size: 0.85em;
            margin-top: 10px;
        }

        @media (max-width: 480px) {
            .card {
                padding: 25px 20px;
            }

            input[type="text"] {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="nttf.png" alt="nttf">
        <h2>Verify OTP</h2>

        <?php if ($success): ?>
            <div class="msg success">OTP verified successfully! Redirecting to reset page...</div>
        <?php elseif (!empty($errorMsg)): ?>
            <div class="msg error"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
            <form method="post">
                <label for="otp" class="note">Enter the 6-digit OTP sent to your email</label>
                <input type="text" id="otp" name="otp" maxlength="6" pattern="\d{6}" placeholder="------" required>
                <button type="submit">Verify OTP</button>
                <div class="timer" id="timer">OTP valid for: <span id="time">05:00</span></div>
            </form>
        <?php endif; ?>
    </div>

    <script>
        // Countdown Timer (5 minutes)
        let timeLeft = 300;
        const timer = document.getElementById("time");
        const countdown = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(countdown);
                timer.innerText = "Expired";
            } else {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timer.innerText = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                timeLeft--;
            }
        }, 1000);
    </script>
</body>
</html>

