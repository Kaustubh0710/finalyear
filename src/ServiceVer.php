<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "happytails";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['email'], $_POST['password'], $_POST['user_type'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $userType = $_POST['user_type'];

        // Map user types to their respective tables and name columns
        $userTables = [
            "Pet Groomers" => ["table" => "groomers", "name_col" => "name"],
            "Veterinarian" => ["table" => "veterinarian", "name_col" => "name"],
            "Boarders" => ["table" => "boarders", "name_col" => "name"]
        ];

        if (!array_key_exists($userType, $userTables)) {
            $_SESSION['login_error'] = "Invalid user type selected.";
            header("Location: ServiceProvLogin.php");
            exit;
        }

        $table = $userTables[$userType]["table"];
        $name_col = $userTables[$userType]["name_col"];

        $sql = "SELECT id, $name_col AS name, password FROM $table WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $email;
                $_SESSION['user_type'] = $userType;

                // Redirect to a common or specific page (adjust if needed)
                header("Location: BoardBookings.php");
                exit;
            } else {
                $_SESSION['login_error'] = "Invalid password.";
            }
        } else {
            $_SESSION['login_error'] = "User not found.";
        }

        $stmt->close();
        header("Location: ServiceProvLogin.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Please fill in all fields.";
        header("Location: ServiceProvLogin.php");
        exit;
    }
} else {
    $_SESSION['login_error'] = "Invalid request.";
    header("Location: ServiceProvLogin.php");
    exit;
}

$conn->close();
?>
