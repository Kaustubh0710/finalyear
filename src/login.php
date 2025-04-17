<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HappyTails - User Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: url('../image/login.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 400px;
            position: relative;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #ff6f00;
            font-size: 24px;
        }

        .login-container input {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .login-container input:focus {
            border-color: #ff6f00;
            outline: none;
        }

        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #ff6f00;
            user-select: none;
        }

        .login-container button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #ff6f00;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .login-container button:hover {
            background: #e65100;
            transform: translateY(-2px);
        }

        .forgot-password, .signup {
            margin-top: 20px;
            font-size: 14px;
            color: #ff6f00;
            text-decoration: none;
            display: inline-block;
        }

        .forgot-password:hover, .signup:hover {
            text-decoration: underline;
        }

        .social-login {
            margin-top: 30px;
        }

        .social-login button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: opacity 0.3s;
        }

        .google-login {
            background: #db4437;
            color: white;
        }

        .facebook-login {
            background: #3b5998;
            color: white;
        }

        .social-login button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login to HappyTails</h2>
        <form id="loginForm" action="LoginVer.php" method="POST">
            <input type="email" id="email" name="email" placeholder="Email Address" required>

            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword(this)">👁️</span>
            </div>

    <button type="submit">Login</button>
</form>

<?php
    session_start();
    if (isset($_SESSION['login_error'])) {
        echo "<p style='color: red; margin-top: 10px; font-size: 14px;'>" . $_SESSION['login_error'] . "</p>";
        unset($_SESSION['login_error']); // clear message after displaying
    }
?>


        <a href="#" class="forgot-password">Forgot Password?</a>
        <a href="Signup.php" class="signup">Create an Account</a>

        <div class="social-login">
            <button class="google-login">Login with Google</button>
            <button class="facebook-login">Login with Facebook</button>
        </div>
    </div>

    <script>
        function togglePassword(el) {
            const input = document.getElementById('password');
            if (input.type === 'password') {
                input.type = 'text';
                el.textContent = '🙈';
            } else {
                input.type = 'password';
                el.textContent = '👁️';
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (email.trim() === "" || password.trim() === "") {
                event.preventDefault();
                return;
            }

            if (!email.includes('@') || password.length < 6) {
                event.preventDefault();
                return;
            }

            // If all validations pass, the form will submit normally and redirect to home.html
        });
    </script>
</body>
</html>
