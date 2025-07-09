<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = dataFilter($_POST['name']);
    $mobile = dataFilter($_POST['mobile']);
    $user = dataFilter($_POST['uname']);
    $email = dataFilter($_POST['email']);
    $pass =    dataFilter(password_hash($_POST['pass'], PASSWORD_BCRYPT));
    $hash = dataFilter(md5(rand(0, 1000)));
    $category = strtolower(dataFilter($_POST['category']));
    $addr = dataFilter($_POST['addr']);

    $_SESSION['Email'] = $email;
    $_SESSION['Name'] = $name;
    $_SESSION['Password'] = $pass;
    $_SESSION['Username'] = $user;
    $_SESSION['Mobile'] = $mobile;
    $_SESSION['Category'] = $category;
    $_SESSION['Hash'] = $hash;
    $_SESSION['Addr'] = $addr;
    $_SESSION['Rating'] = 0;
}

require '../db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$length = strlen($mobile);

if ($length != 11) {
    $_SESSION['message'] = "Invalid Mobile Number !!!";
    header("location: error.php");
    die();
}
if (!isset($_POST['category'])) {
    $_SESSION['message'] = "Category is not selected!";
    header("location: error.php");
    exit();
}

if ($category == "farmer") {
    $sql = "SELECT * FROM farmer WHERE femail='$email'";

    $result = mysqli_query($conn, "SELECT * FROM farmer WHERE femail='$email'") or die($mysqli->error());

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "User with this email already exists!";
        //echo $_SESSION['message'];
        header("location: error.php");
    } else {
        $sql = "INSERT INTO farmer (fname, fusername, fpassword, fhash, fmobile, femail, faddress)
    			VALUES ('$name','$user','$pass','$hash','$mobile','$email','$addr')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['Active'] = 0;
            $_SESSION['logged_in'] = true;

            $_SESSION['picStatus'] = 0;
            $_SESSION['picExt'] = "png";

            $sql = "SELECT * FROM farmer WHERE fusername='$user'";
            $result = mysqli_query($conn, $sql);
            $User = $result->fetch_assoc();
            $_SESSION['id'] = $User['fid'];

            if ($_SESSION['picStatus'] == 0) {
                $_SESSION['picId'] = 0;
                $_SESSION['picName'] = "profile0.png";
            } else {
                $_SESSION['picId'] = $_SESSION['id'];
                $_SESSION['picName'] = "profile" . $_SESSION['picId'] . "." . $_SESSION['picExt'];
            }

            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'smartsamuel017@gmail.com';
                $mail->Password = 'ptdvusgyqqewgtgc';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('smartsamuel017@gmail.com', 'EdwinProjectCorrrect');
                $mail->addAddress($email, $user);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Account Verification ( Farm2Table.com )';
                $mail->Body    = "
                Hello $user,<br><br>
                Thank you for signing up!<br><br>
                Please click this link to activate your account:<br>
                <a href='http://localhost/AgroCulture/Login/verify.php?email=$email&hash=$hash'>Verify Account</a>";

                $mail->send();
                $_SESSION['message'] = "Confirmation link has been sent to $email, please verify your account by clicking on the link in the message!";
            } catch (Exception $e) {
                $_SESSION['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            header("location: profile.php");
        } else {
            //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $_SESSION['message'] = "Registration failed!";
            header("location: error.php");
        }
    }
} 
else if ($category == "buyer") {
    $sql = "SELECT * FROM buyer WHERE bemail='$email'";

    $result = mysqli_query($conn, "SELECT * FROM buyer WHERE bemail='$email'") or die($mysqli->error());

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "User with this email already exists!";
        //echo $_SESSION['message'];
        header("location: error.php");
    } else {
        $sql = "INSERT INTO buyer (bname, busername, bpassword, bhash, bmobile, bemail, baddress)
    			VALUES ('$name','$user','$pass','$hash','$mobile','$email','$addr')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['Active'] = 0;
            $_SESSION['logged_in'] = true;

            $sql = "SELECT * FROM buyer WHERE busername='$user'";
            $result = mysqli_query($conn, $sql);
            $User = $result->fetch_assoc();
            $_SESSION['id'] = $User['bid'];

            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'smartsamuel017@gmail.com';
                $mail->Password = 'ptdvusgyqqewgtgc';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('SmartSamuel017@gmail.com', 'EdwinProjectCorrrect');
                $mail->addAddress($email, $user);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Account Verification ( Farm2Table.com )';
                $mail->Body    = "
                Hello $user,<br><br>
                Thank you for signing up!<br><br>
                Please click this link to activate your account:<br>
                <a href='http://localhost/AgroCulture/Login/verify.php?email=$email&hash=$hash'>Verify Account</a>";

                $mail->send();
                $_SESSION['message'] = "Confirmation link has been sent to $email, please verify your account by clicking on the link in the message!";
            } catch (Exception $e) {
                $_SESSION['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            header("location: profile.php");
        } else {
            //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $_SESSION['message'] = "Registration not successfull!";
            header("location: error.php");
        }
    }
}
else {
    $_SESSION['message'] = "Write Farmer or Buyer!";
    header("location: error.php");
}

function dataFilter($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
