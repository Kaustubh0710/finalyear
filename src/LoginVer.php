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
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT id, full_name, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $email;
                header("Location: home.php");
                exit;
            } else {
                $_SESSION['login_error'] = "Invalid password.";
                header("Location: login.php");
                exit;
            }
        } else {
            $_SESSION['login_error'] = "User not found.";
            header("Location: login.php");
            exit;
        }

        $stmt->close();
    } else {
        $_SESSION['login_error'] = "Please fill in both fields.";
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['login_error'] = "Invalid request.";
    header("Location: login.php");
    exit;
}

$conn->close();
?>
