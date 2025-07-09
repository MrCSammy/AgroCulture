<?php
session_start();
require '../db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $user = $_POST['user'];
    $hash = $_POST['hash'];

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'smartsamuel017@gmail.com';
        $mail->Password = 'ptdvusgyqqewgtgc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('smartsamuel017@gmail.com', 'EdwinProjectCorrrect');
        $mail->addAddress($email, $user);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Verification ( Farm2Table.com )';
        $mail->Body    = "
        Hello $user,<br><br>
        Thank you for signing up!<br><br>
        Please click this link to activate your account:<br>
        <a href='http://localhost/AgroCulture/Login/verify.php?email=$email&hash=$hash'>Verify Account</a>";

        $mail->send();
        $_SESSION['message'] = "Verification mail resent to $email.";
        header("location: error.php");
    } catch (Exception $e) {
        $_SESSION['message'] = "Resend failed. Mailer Error: {$mail->ErrorInfo}";
        header("location: error.php");
    }
} else {
    $_SESSION['message'] = "Invalid Request.";
    header("location: error.php");
}
?>
