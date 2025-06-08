<?php
session_start();
if (!isset($_SESSION['full_name'])) {
    $redirect = basename($_SERVER['PHP_SELF']); // current page
    echo "<script>
        const confirmRedirect = confirm('You must be logged in to access this page. Do you want to login now?');
        if (confirmRedirect) {
            // Redirect to login with redirect back after login
            window.location.href = 'login.php?redirect=$redirect';
        } else {
            // Go back to previous page (where user came from)
            if (document.referrer) {
                window.location.href = document.referrer;
            } else {
                window.location.href = 'index.php'; // fallback
            }
        }
    </script>";
    exit();
}
?>
