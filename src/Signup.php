<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HappyTails - Sign Up</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: url('../image/signup.jpg');
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .signup-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }

        .signup-container h2 {
            color: #ff6f00;
            margin-bottom: 20px;
        }

        .signup-container .input-wrapper {
            position: relative;
        }

        .signup-container input[type="text"],
        .signup-container input[type="email"],
        .signup-container input[type="tel"],
        .signup-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .signup-container small {
            font-size: 12px;
            color: #666;
            display: block;
            margin-top: -5px;
            margin-bottom: 10px;
            text-align: left;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            user-select: none;
        }

        .signup-container button {
            width: 100%;
            padding: 12px;
            background: #ff6f00;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        .signup-container button:hover {
            background: #e65100;
        }

        .login-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .login-link a {
            color: #ff6f00;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="signup-container">
        <h2>Create Your Account</h2>
        <form action="SignupVer.php" method="POST" id="signup-form">
            <input type="text" name="full_name" placeholder="Full Name" required
                   pattern="[A-Za-z ]+" title="Only letters and spaces allowed in full name.">
            
            <input type="email" name="email" placeholder="Email Address" required
                   pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Email must be in the format: example@gmail.com">
            
            <input type="tel" name="phone" placeholder="Phone Number" required
                   pattern="[0-9]{10}" title="Phone number must be exactly 10 digits">

            <div class="input-wrapper">
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    placeholder="Password" 
                    required 
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                    title="At least 8 characters, with uppercase, lowercase, number, and special character.">
                <span class="toggle-password" onclick="togglePassword('password', this)">👁️</span>
            </div>
            <small>Password must be at least 8 characters, including uppercase, lowercase, number, and special character.</small>

            <div class="input-wrapper">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                <span class="toggle-password" onclick="togglePassword('confirm_password', this)">👁️</span>
            </div>

            <button type="submit">Sign Up</button>
        </form>
        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("signup-form");

            form.addEventListener("submit", function(e) {
                const password = document.querySelector("input[name='password']").value;
                const confirmPassword = document.querySelector("input[name='confirm_password']").value;
                const email = document.querySelector("input[name='email']").value;
                const emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

                if (!emailPattern.test(email)) {
                    alert("Email must be in the format: example@gmail.com");
                    e.preventDefault();
                    return;
                }

                if (password !== confirmPassword) {
                    alert("Passwords do not match.");
                    e.preventDefault();
                }
            });
        });

        function togglePassword(id, el) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                el.textContent = "🙈";
            } else {
                input.type = "password";
                el.textContent = "👁️";
            }
        }
    </script>

</body>
</html>
