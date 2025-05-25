<?php
session_start();

// Check if the user has completed the signup process
if (!isset($_SESSION['signup_completed']) || !$_SESSION['signup_completed']) {
    // Redirect to signup page or show an error message
    header('Location: ServiceProvSignup.php'); // Or any other page you want to redirect to
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "happytails";
$conn = new mysqli($host, $user, $pass, $dbname);

$boarder = $_SESSION['boarder'];
$email = $boarder['email'];

$error = "";

if (isset($_POST['submit_all'])) {
  // --- Collect all form data ---
  $name = $_POST['full_name'];
  $contact = $_POST['contact'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  $pin_code = $_POST['pin'];
  $cat_daycare = $_POST['cat_daycare'];
  $cat_overnight = $_POST['cat_overnight'];
  $dog_daycare = $_POST['dog_daycare'];
  $dog_overnight = $_POST['dog_overnight'];
  $desc = $_POST['description'];

  // Validate certificate number format
  if (!preg_match("/^[A-Z]{2,3}\d{6,8}$/", $desc)) {
    $error = "❌ Invalid certificate number. Use 2–3 uppercase letters followed by 6–8 digits (e.g., AB123456).";
  }

  // --- Upload profile picture ---
  $profile_picture = $_FILES['photo']['name'];
  $profile_path = "image/" . basename($profile_picture);
  move_uploaded_file($_FILES['photo']['tmp_name'], "../" . $profile_path);

  // --- Upload identity proof ---
  $identity_proof = $_FILES['idproof']['name'];
  $id_path = "image/" . basename($identity_proof);
  move_uploaded_file($_FILES['idproof']['tmp_name'], "../" . $id_path);

  // --- Upload certificates ---
  $certList = [];
  foreach ($_FILES['certificates']['tmp_name'] as $key => $tmp_name) {
      $certName = $_FILES['certificates']['name'][$key];
      $certPath = "image/" . basename($certName);
      move_uploaded_file($tmp_name, "../" . $certPath);
      $certList[] = $certPath;
  }
  $certs_str = implode(",", $certList);

  $sql = "UPDATE boarders SET 
  name=?, 
  contact=?, 
  profile_picture=?, 
  identity_proof=?, 
  address=?, 
  city=?, 
  state=?, 
  pin_code=?, 
  cat_daycare=?, 
  cat_overnight=?, 
  dog_daycare=?, 
  dog_overnight=?,
  certificates=?, 
  certificate_description=? 
  WHERE email=?";

$stmt = $conn->prepare($sql);

// Bind the variables with correct types
$stmt->bind_param(
  "ssssssssddddsss",  // Correct placeholder string
  $name, 
  $contact, 

  $profile_path, 
  $id_path,
  $address, 
  $city, 
  $state, 
  $pin_code,
  $cat_daycare, 
  $cat_overnight, 
  $dog_daycare, 
  $dog_overnight,
  $certs_str, 
  $desc,
  $email
);

// Execute the statement
$stmt->execute();


  // ✅ Redirect after successful update
  echo "<script>window.location.replace('ServiceProvLogin.php');</script>";
  exit();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Partner Profile - HappyTails</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background: url('../image/Board.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #333;
    }

    .wrapper {
      background-color: rgba(255, 255, 255, 0.9);
      max-width: 800px;
      margin: 50px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    h2 {
      text-align: center;
      margin-bottom: 10px;
      color: #1a1b4b;
    }

    p {
      text-align: center;
      margin-bottom: 30px;
    }

    .accordion {
      background-color: #1a1b4b;
      color: #fff;
      cursor: pointer;
      padding: 15px 20px;
      width: 100%;
      border: none;
      text-align: left;
      outline: none;
      font-size: 16px;
      border-radius: 6px;
      margin-top: 10px;
      transition: background-color 0.3s ease;
    }

    .active, .accordion:hover {
      background-color: #2c2d6e;
    }

    .panel {
      display: none;
      background-color: #f9f9f9;
      overflow: hidden;
      border-radius: 0 0 6px 6px;
      padding: 20px;
      margin-bottom: 15px;
      animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }

    label {
      display: block;
      margin: 12px 0 6px;
      font-weight: bold;
    }

    input[type="file"],
    input[type="text"],
    input[type="email"],
    input[type="number"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #aaa;
      border-radius: 6px;
    }

    button.submit-btn {
      margin-top: 20px;
      padding: 12px 20px;
      background-color: #1a1b4b;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 15px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button.submit-btn:hover {
      background-color: #2c2d6e;
    }
  </style>
</head>
<body>

<div class="wrapper">
  <h2>Basic Information</h2>
  <p>Please fill out your profile details for verification and onboarding.</p>

  <form method="post" enctype="multipart/form-data">

    <button class="accordion">Personal Details</button>
    <div class="panel">
        <label>Your Picture</label>
        <input type="file" name="photo" accept="image/*" required>

        <label>Full Name</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($boarder['name']); ?>" required>

        <label>Email</label>
        <input type="email" value="<?php echo htmlspecialchars($boarder['email']); ?>" readonly>

        <label>Contact Number</label>
        <input type="number" name="contact" value="<?php echo htmlspecialchars($boarder['contact']); ?>" required>

        <label>Identity Proof (Aadhaar, DL, etc.)</label>
        <input type="file" name="idproof" accept="image/*,application/pdf" required>
    </div>

    <button class="accordion">Address Details</button>
    <div class="panel">
        <label>Full Address</label>
        <input type="text" name="address" required>

        <label>City</label>
        <input type="text" name="city" required>

        <label>State</label>
        <input type="text" name="state" required>

        <label>PIN Code</label>
        <input type="number" name="pin" required>
    </div>

    <button class="accordion">Service Price</button>
    <div class="panel">
        <h4>Cat Services</h4>
        <label>Day Care (Per Hour)</label>
        <input type="number" name="cat_daycare" placeholder="₹80" required>

        <label>Overnight (Per Night)</label>
        <input type="number" name="cat_overnight" placeholder="₹979" required>

        <h4>Dog Services</h4>
        <label>Day Care for Dog (Per Hour)</label>
        <input type="number" name="dog_daycare" placeholder="₹80" required>

        <label>Overnight Stay for Dog (Per Night)</label>
        <input type="number" name="dog_overnight" placeholder="₹979" required>
    </div>

    <button class="accordion">Award & Certificate</button>
    <div class="panel">
        <label>Upload Certificates</label>
        <input type="file" name="certificates[]" multiple required>

        <label>Description (Certificate No.)</label>
        <input type="text" name="description" pattern="[A-Z]{2,3}\d{6,8}" title="Enter correct pattern" required value="<?php echo htmlspecialchars($_POST['description'] ?? ''); ?>">

        <?php if (!empty($error)) : ?>
            <p style="color:red; font-weight:bold;"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>

    <!-- ✅ Single submit button here -->
    <button class="submit-btn" name="submit_all">Submit All Details</button>

  </form>
</div>

<script>

  const acc = document.getElementsByClassName("accordion");
  for (let i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
      this.classList.toggle("active");
      const panel = this.nextElementSibling;
      if (panel.style.display === "block") {
        panel.style.display = "none";
      } else {
        panel.style.display = "block";
      }
    });
  }
</script>

</body>
</html>
