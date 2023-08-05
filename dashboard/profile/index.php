<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE);
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
    <title>Profils - <?= $_GET['username']?></title>
</head>
<?php include '../../header.php'?>
    <section class="home">
    <?php
if (isset($_GET['username'])) {
    include '../../authdb.php';
    $user = $_GET['username'];
    $sqlusername = mysqli_real_escape_string($conn, $user);
    $sql = "SELECT username FROM authme WHERE username = '$sqlusername'";
    $result = $conn->query($sql);

    // Fetch the data from the query result
    if ($row = $result->fetch_assoc()) {
        include 'profile.php';
    } else {
        // The user does not exist in the database
        include 'notfound.php';
    }
} else {
    include 'notfound.php';
}
?>

    </section>
    <script src ="../../js/header.js"></script>

</body>
</html>
