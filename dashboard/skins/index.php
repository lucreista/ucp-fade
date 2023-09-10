<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../functions.php';
cookieCheck();
if (isLoggedIn()) {
} else {
    header("Location: https://ucp.fade.lv/");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <title>Samainīt skinu - ucp.fade.lv</title>
</head>
<?php include '../../header.php'?>
    <section class="home">
<?php include 'skins.php'?>
    </section>
    <script src ="../../js/header.js"></script>

</body>
</html>
