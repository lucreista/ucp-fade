<!DOCTYPE html>
<html lang="en">
 <head>
   <title>Login page</title>
   <link rel="stylesheet" type="text/css" href="css/login.css">
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="Lietotāju kontrolpanelis priekš mc.fade.lv">
   <meta name="og:title" content="ucp.fade.lv">
   <meta name="og:description" content="Lietotāju kontrolpanelis priekš mc.fade.lv">
   <meta name="og:url" content="https://ucp.fade.lv">
   <meta name="og:type" content="website">
 </head>
 <body>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'functions.php';
cookieCheck();
if (isLoggedIn()) {
    header("Location: https://ucp.fade.lv/dashboard");
} else {

}
echo '<video muted id="video" autoplay loop>
<source src="https://i.imgur.com/soGok3O.mp4" type="video/mp4"></source>
</video>';
require 'db.php';
require 'AuthMeController.php';
require 'Sha256.php';
$authme_controller = new Sha256();

$action = get_from_post_or_empty('action');
$user = get_from_post_or_empty('fadeuser');
$pass = get_from_post_or_empty('password');

$was_successful = false;
if ($action && $user && $pass) {
    if ($action === 'Log in') {
        $was_successful = process_login($user, $pass, $authme_controller);
    }
}

if (!$was_successful) {
    echo '
<form method="post">
 <table>
 <td><h1> Login </h1><td>
   <tr><td><input type="text" value="' . htmlspecialchars($user) . '" placeholder="Lietotājvārds" name="fadeuser" /></td></tr>
   <tr><td><input type="password" value="' . htmlspecialchars($pass) . '" placeholder="Parole" name="password" /></td></tr>
   <tr>
     <td><input class="login" type="submit" name="action" value="Log in" /></td>
   </tr>
   <td><p>Problēmas tikt iekšā?</p><p>Sazinies ar administrātoru iekš discord!</p><p><a href="fade.lv/discord">Discord serveris</a> </td>
 </table>
</form>';
}

function get_from_post_or_empty($index_name) {
    return trim(
        filter_input(INPUT_POST, $index_name, FILTER_UNSAFE_RAW, FILTER_REQUIRE_SCALAR | FILTER_FLAG_STRIP_LOW)
            ?: '');
}
// Login logic
function process_login($user, $pass, AuthMeController $controller) {
    include 'db.php';
    $fadesession = "fadesession";
    if ($controller->checkPassword($user, $pass)) {
        $_SESSION["username"] = $user;
        $token = bin2hex(random_bytes(32)); // izveido one time use kodu
        ucpCheck($user,$token);
        $sqlusername = mysqli_real_escape_string($conn, $_SESSION['username']);
        $sqlmcusername = mysqli_real_escape_string($conn, $user);
        $dateNow = date('m/d/Y h:i a', time());
        $sql = "UPDATE users SET token = '$token', mcusername = '$sqlusername', lastloginweb = '$dateNow' WHERE mcusername = '$sqlmcusername' OR mcusername = '$sqlusername'";
        $result = $conn->query($sql);
        setcookie($fadesession, $token, time() + (86400 * 7), "/");
        header("Location: https://ucp.fade.lv/dashboard");
        return true;
    } else {
        echo '<h1>Error</h1><p> Invalid username or password.</p>';
    }
    return false;
}
// parbauda vai users pastav ieks ucp db
function ucpCheck($user,$token) {
    include 'db.php';
    $sqlusername = mysqli_real_escape_string($conn, $user);
    $sql = "SELECT mcusername FROM users WHERE mcusername = '$sqlusername'";
    $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            //neko nedarit, jo users jau ir ieprieks bijis ucp
        } else {
            // pirmo reizi ucp loginojas
            $date = date('m/d/Y h:i a', time());
            $sql = "INSERT INTO `users` (mcusername, token, lastloginweb, coins, customskin) VALUES ('$sqlusername', '$token', '$date', '0', '0')";
            $result = $conn->query($sql);
        }
}

?>

 </body>
</html>