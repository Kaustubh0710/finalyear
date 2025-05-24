<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "happytails";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Validation
    if (empty($name)) {
        $error = "Name cannot be empty.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error = "Name can only contain letters and spaces.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $error = "Contact must be exactly 10 digits.";
    } elseif ($role === '') {
        $error = "Please select a role.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/", $password)) {
        $error = "Password must be at least 10 characters long and strong.";
    } else {
        // Check email existence based on role
        if ($role == 'Pet Grooming') {
            $checkEmail = $conn->prepare("SELECT id FROM groomers WHERE email = ?");
        } else {
            $checkEmail = $conn->prepare("SELECT id FROM boarders WHERE email = ?");
        }
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $checkEmail->store_result();

        if ($checkEmail->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if ($role == 'Pet Grooming') {
                // Insert into groomers table
                $stmt = $conn->prepare("INSERT INTO groomers (full_name, email, phone) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $contact);

                if ($stmt->execute()) {
                    $_SESSION['groomer'] = [
                        'full_name' => $name,
                        'email' => $email,
                        'phone' => $contact
                    ];
                    $_SESSION['signup_completed'] = true;
                    header("Location: GroomersDetails.php");
                    exit();
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            } else {
                // Insert into boarders table
                $stmt = $conn->prepare("INSERT INTO boarders (name, email, contact, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $email, $contact, $hashedPassword);

                if ($stmt->execute()) {
                    $_SESSION['boarder'] = [
                        'name' => $name,
                        'email' => $email,
                        'contact' => $contact
                    ];
                    $_SESSION['signup_completed'] = true;
                    switch ($role) {
                        case 'Veterinary':
                            header("Location: veterinary.php");
                            break;
                        case 'Boarding':
                            header("Location: Boarders.php");
                            break;
                        default:
                            $error = "Please select a valid role.";
                    }
                    exit();
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            }
            $stmt->close();
        }
        $checkEmail->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Partner Signup - HappyTails</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: url('../image/DogsCat.webp') no-repeat center center/cover;
      margin: 0;
      padding: 0;
      background-attachment: fixed;
    }
    .container {
      width: 90%;
      max-width: 900px; /* Restrict container width for better focus */
      margin: 130px auto; /* Center and reduce top space */
      padding: 30px 20px;
      border-radius: 10px; /* Rounded corners */
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Soft shadow */
      margin-right: 390px
    }
    label, p {
      color: white;
      font-weight: bold;
    }
    h2 {
      color: black;
    }
    .error-message {
      color: red;
      font-weight: bold;
      margin-bottom: 15px;
    }
    form {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .form-group {
      flex: 1 1 45%;
      display: flex;
      flex-direction: column;
      position: relative;
    }
    .form-group input,
    .form-group select {
      padding: 10px 40px 10px 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 26px;
      cursor: pointer;
      font-size: 18px;
      color: #444;
      user-select: none;
    }
    .btn {
      background-color: #1a1b4b;
      color: #fff;
      padding: 12px 20px;
      font-size: 16px;
      border: none;
      border-radius: 4px;
      margin: 20px auto 0 auto;
      cursor: pointer;
      display: block;
    }
    .btn:hover {
      background-color: #2c2d6e;
    }
    .login-link {
        margin-left: 320px;
        margin-top: 15px;
        font-size: 14px;
        color: white;
    }
    .login-link a {
        color: skyblue;
        text-decoration: none;
    }
    .login-link a:hover {
        text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Sign Up To Become HappyTails Partner</h2>
  <p>Please fill in this form to create an account.</p>

  <?php if (!empty($error)): ?>
    <div class="error-message"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="post" action="">
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
    </div>
    <div class="form-group">
      <label for="contact">Contact Number</label>
      <input type="text" name="contact" id="contact" maxlength="10" value="<?php echo isset($_POST['contact']) ? $_POST['contact'] : ''; ?>" required>
    </div>
    <div class="form-group">
      <label for="role">What do you do?</label>
      <select name="role" id="role" required>
        <option value="">Select an option</option>
        <option value="Pet Grooming" <?php echo (isset($_POST['role']) && $_POST['role'] === 'Pet Grooming') ? 'selected' : ''; ?>>Pet Grooming</option>
        <option value="Veterinary" <?php echo (isset($_POST['role']) && $_POST['role'] === 'Veterinary') ? 'selected' : ''; ?>>Veterinary</option>
        <option value="Boarding" <?php echo (isset($_POST['role']) && $_POST['role'] === 'Boarding') ? 'selected' : ''; ?>>Boarding</option>
      </select>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>" minlength="10" required>
      <span class="toggle-password" onclick="togglePassword('password', this)">👁️</span>
    </div>
    <div class="form-group">
      <label for="confirm">Confirm Password</label>
      <input type="password" name="confirm" id="confirm" value="<?php echo isset($_POST['confirm']) ? $_POST['confirm'] : ''; ?>" required>
      <span class="toggle-password" onclick="togglePassword('confirm', this)">👁️</span>
    </div>
    <button class="btn" type="submit">Become a Partner</button>
  </form>
  <p class="login-link">Already have an account? <a href="ServiceProvLogin.php">Login here</a></p>
</div>

<script>

            function preventBack(){
                window.history.forward();
            }
            setTimeout("preventBack()",0);
            window.onunload=function(){null};

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
