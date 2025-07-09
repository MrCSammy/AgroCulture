<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Farm2Table</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../bootstrap\css\bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../bootstrap\js\bootstrap.min.js"></script>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/skel.min.js"></script>
    <script src="../js/skel-layers.min.js"></script>
    <script src="../js/init.js"></script>
    <link rel="stylesheet" href="../css/skel.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/style-xlarge.css" />
</head>

<body>
    <?php
    require 'menu.php';
    ?>

    <section id="banner" class="wrapper">
        <div class="container">
            <header class="major">
                <h2>ERROR</h2>
            </header>
            <p>
                <?php
                $page = $_SERVER['HTTP_REFERER'];
                if (isset($_SESSION['message']) and !empty($_SESSION['message'])) {
                    echo $_SESSION['message'];
                } else {
                    header("Location: ../index.php");
                }
                ?>
            </p><br />
            <a href="<?= $page ?>" class="button special">Retry</a>


            <?php $_SESSION['message'] = ""; ?>

            <?php if (strpos($_SESSION['message'], 'Mailer Error') !== false): ?>
                <form action="resend_mail.php" method="post">
                    <input type="hidden" name="email" value="<?= $_SESSION['Email'] ?>">
                    <input type="hidden" name="user" value="<?= $_SESSION['Username'] ?>">
                    <input type="hidden" name="hash" value="<?= $_SESSION['Hash'] ?>">
                    <button class="button special" type="submit">Resend Verification Email</button>
                </form>
            <?php endif; ?>

</body>

</html>