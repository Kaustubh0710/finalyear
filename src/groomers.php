<?php
session_start();

if (!isset($_SESSION['groomer'])) {
    header('Location: ServiceProvLogin.php');
    exit();
}

$full_name = $_SESSION['groomer']['full_name'];
$email = $_SESSION['groomer']['email'];
$phone = $_SESSION['groomer']['phone'];

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = 'localhost';
$dbname = 'happytails';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $address_line1 = trim($_POST['address_line1'] ?? '');
    $address_line2 = trim($_POST['address_line2'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');
    $grooming_price = trim($_POST['grooming_price'] ?? '');
    $service_type = trim($_POST['service_type'] ?? '');
    $pet_type_serviced = trim($_POST['pet_type_serviced'] ?? '');
    $pet_size_serviced = trim($_POST['pet_size_serviced'] ?? '');
    $availability = trim($_POST['availability'] ?? '');
    $years_experience = trim($_POST['years_experience'] ?? '');
    $awards = trim($_POST['awards'] ?? '');
    $special_skills = trim($_POST['special_skills'] ?? '');

    $errors = [];

    // Validation
    if (!$address_line1) $errors[] = "Address Line 1 is required.";
    if (!$city) $errors[] = "City is required.";
    if (!$state) $errors[] = "State is required.";
    if (!$zip_code) $errors[] = "Zip Code is required.";
    if (!$grooming_price) {
        $errors[] = "Grooming price is required.";
    } elseif ($grooming_price < 0) {
        $errors[] = "Grooming price must be positive.";
    }
    if (!$availability) $errors[] = "Availability is required.";
    if ($years_experience === '') {
        $errors[] = "Years of experience is required.";
    } elseif ($years_experience < 0) {
        $errors[] = "Years of experience must be positive.";
    }

    // Handle profile photo upload
    $profile_photo = null;
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_file_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['profile_photo']['type'], $allowed_file_types)) {
            $errors[] = "Only JPEG, PNG, and GIF files are allowed.";
        }

        if (empty($errors)) {
            $upload_dir = 'image/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $filename = basename($_FILES['profile_photo']['name']);
            $target_file = $upload_dir . $filename;

            echo "<pre>";
            print_r($_FILES['profile_photo']);
            echo "</pre>";


            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_file)) {
                $profile_photo = $filename;
            } else {
                $errors[] = "Failed to upload the profile photo.";
            }
        }
    }

    if (!empty($errors)) {
        echo "<script>alert('" . implode("\\n", $errors) . "'); window.history.back();</script>";
        exit;
    }

    // Update groomer data
    try {
        if ($profile_photo) {
            // If new profile photo uploaded
            $sql = "UPDATE groomers SET
                profile_photo = ?, 
                address_line1 = ?, 
                address_line2 = ?, 
                city = ?, 
                state = ?, 
                zip_code = ?, 
                grooming_price = ?, 
                service_type = ?, 
                pet_type_serviced = ?, 
                pet_size_serviced = ?, 
                availability = ?, 
                years_experience = ?, 
                awards = ?, 
                special_skills = ?
            WHERE email = ?";

            $params = [
                $profile_photo, $address_line1, $address_line2, $city, $state, $zip_code,
                $grooming_price, $service_type, $pet_type_serviced, $pet_size_serviced,
                $availability, $years_experience, $awards, $special_skills, $email
            ];
        } else {
            // No profile photo uploaded
            $sql = "UPDATE groomers SET
                address_line1 = ?, 
                address_line2 = ?, 
                city = ?, 
                state = ?, 
                zip_code = ?, 
                grooming_price = ?, 
                service_type = ?, 
                pet_type_serviced = ?, 
                pet_size_serviced = ?, 
                availability = ?, 
                years_experience = ?, 
                awards = ?, 
                special_skills = ?
            WHERE email = ?";

            $params = [
                $address_line1, $address_line2, $city, $state, $zip_code,
                $grooming_price, $service_type, $pet_type_serviced, $pet_size_serviced,
                $availability, $years_experience, $awards, $special_skills, $email
            ];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo "<script>alert('Profile updated successfully!'); window.location.href='groomers.html';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error updating data: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    }
}

$pdo = null;
?>
