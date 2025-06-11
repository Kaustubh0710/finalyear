<?php
session_start();
if (!isset($_SESSION['email'])) {
    die("Unauthorized: No email found in session.");
}
$email = $_SESSION['email'];

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "happytails";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ✅ Check if user already has 3 pets registered
    $limit_sql = "SELECT COUNT(*) AS count FROM matingregister WHERE email = ?";
    $limit_stmt = $conn->prepare($limit_sql);
    $limit_stmt->bind_param("s", $email);
    $limit_stmt->execute();
    $limit_result = $limit_stmt->get_result();
    $limit_data = $limit_result->fetch_assoc();

    if ($limit_data['count'] >= 3) {
        echo "<script>alert('You can only register up to 3 pets.'); window.location.href='dogmating.php';</script>";
        exit;
    }

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

    // ✅ Check if this pet (email + breed + pet_name) is already registered
    $duplicate_sql = "SELECT * FROM matingregister WHERE email = ? AND breed = ? AND pet_name = ?";
    $duplicate_stmt = $conn->prepare($duplicate_sql);
    $duplicate_stmt->bind_param("sss", $email, $breed, $petName);
    $duplicate_stmt->execute();
    $duplicate_result = $duplicate_stmt->get_result();

    if ($duplicate_result->num_rows > 0) {
        echo "<script>alert('This pet is already registered!'); window.location.href='dogmating.php';</script>";
        exit;
    }

    // Handle uploaded image
    if (isset($_FILES['petImage']) && $_FILES['petImage']['error'] == 0) {
        $imageName = uniqid() . "_" . basename($_FILES["petImage"]["name"]);
        $targetDirectory = "uploads/";
        $targetFile = $targetDirectory . $imageName;

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        if (!move_uploaded_file($_FILES["petImage"]["tmp_name"], $targetFile)) {
            echo "Error uploading image.";
            exit;
        }
    } else {
        $imageName = '';
    }

    // Insert into database including email
    $sql = "INSERT INTO matingregister (
        email, pet_name, pet_type, breed, age, weight, gender, vaccination_status, sociable, potty_trained, aggressive_level, pet_image
    ) VALUES (
        '$email', '$petName', '$petType', '$breed', '$age', '$weight', '$gender', '$vaccination', '$sociable', '$potty_trained', '$aggressive', '$imageName'
    )";

    if ($conn->query($sql) === TRUE) {
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
