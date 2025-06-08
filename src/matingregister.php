<?php

// Database connection details
$servername = "localhost";
$username = "root";   // Change if needed
$password = "";       // Change if needed
$dbname = "happytails";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connections
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Collect and sanitize form inputs
  $petName = $conn->real_escape_string($_POST['petName']);
  $breed = $conn->real_escape_string($_POST['breed']);
  $age = $conn->real_escape_string($_POST['age']);
  $weight = $conn->real_escape_string($_POST['weight']);
  $gender = isset($_POST['gender']) ? $conn->real_escape_string($_POST['gender']) : '';
  $vaccination = isset($_POST['vaccination']) ? $conn->real_escape_string($_POST['vaccination']) : '';
  $sociable = isset($_POST['sociable']) ? $conn->real_escape_string($_POST['sociable']) : '';
  $potty_trained = isset($_POST['potty_trained']) ? $conn->real_escape_string($_POST['potty_trained']) : '';
  $aggressive = isset($_POST['aggressive']) ? $conn->real_escape_string($_POST['aggressive']) : '';
  $petType = isset($_POST['petType']) ? $conn->real_escape_string($_POST['petType']) : '';

  // Handle the uploaded file
  if (isset($_FILES['petImage']) && $_FILES['petImage']['error'] == 0) {
    $imageName = uniqid() . "_" . basename($_FILES["petImage"]["name"]);
    $targetDirectory = "uploads/";
    $targetFile = $targetDirectory . $imageName;

    // Create uploads directory if not exists
    if (!is_dir($targetDirectory)) {
      mkdir($targetDirectory, 0777, true);
    }

    // Move the uploaded file
    if (move_uploaded_file($_FILES["petImage"]["tmp_name"], $targetFile)) {
      // File uploaded successfully
    } else {
      echo "Error uploading image.";
      exit;
    }
  } else {
    $imageName = '';
  }

  // Insert into database
  $sql = "INSERT INTO matingregister (
    pet_name, pet_type, breed, age, weight, gender, vaccination_status, sociable, potty_trained, aggressive_level, pet_image
  ) VALUES (
    '$petName', '$petType', '$breed', '$age', '$weight', '$gender', '$vaccination', '$sociable', '$potty_trained', '$aggressive', '$imageName'
  )";

  if ($conn->query($sql) === TRUE) {
    // Success: Redirect based on pet type
    if ($petType === 'dog') {
      echo "<script>alert('Pet registered successfully!'); window.location.href='dogmating.php';</script>";
    } elseif ($petType === 'cat') {
      echo "<script>alert('Pet registered successfully!'); window.location.href='catmating.php';</script>";
    } else {
      echo "<script>alert('Pet registered, but unknown pet type!');</script>";
    }
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

} else {
  echo "Invalid request.";
}

$conn->close();
?>