<?php
session_start();
require '../db.php';

if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])) {
    $email = htmlspecialchars(trim($_GET['email']));
    $hash = htmlspecialchars(trim($_GET['hash']));

    // First, check the 'farmer' table
    $sql = "SELECT * FROM farmer WHERE femail='$email' AND fhash='$hash' AND factive='0'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $_SESSION['message'] = "Your account has been activated!";
        $update = "UPDATE farmer SET factive='1' WHERE femail='$email'";
        $conn->query($update);
        $_SESSION['Active'] = 1;

        header("location: success.php");
        exit();
    } else {
        // If not found in 'farmer', check the 'buyer' table
        $sql = "SELECT * FROM buyer WHERE bemail='$email' AND bhash='$hash' AND bactive='0'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $_SESSION['message'] = "Your account has been activated!";
            $update = "UPDATE buyer SET bactive='1' WHERE bemail='$email'";
            $conn->query($update);
            $_SESSION['Active'] = 1;

            header("location: success.php");
            exit();
        } else {
            $_SESSION['message'] = "Account has already been activated or the URL is invalid!";
            header("location: error.php");
            exit();
        }
    }
} else {
    $_SESSION['message'] = "Invalid credentials provided for account verification!";
    header("location: error.php");
    exit();
}
?>
