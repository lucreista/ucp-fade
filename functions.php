<?php
function cookieCheck() {
    include 'db.php';
    if (isset($_COOKIE["fadesession"])) {
    $realcookie = mysqli_real_escape_string($conn, $_COOKIE['fadesession']);
    $sql = "SELECT token,mcusername FROM users WHERE token = '$realcookie'";
    $result = $conn->query($sql);
    $userInfo = $result->fetch_assoc();
    $userToken = $userInfo["token"];
    if ($userToken == $_COOKIE['fadesession']) {
        $_SESSION["username"] = $userInfo["mcusername"];
        $_SESSION["uuid"] = offlineUUID($userInfo["mcusername"]);
        } else
            logOut();
    }
}


function logOut() {
    session_unset();
    session_destroy();
    setcookie("fadesession","", time()-3600, "/");
    unset($_COOKIE['fadesession']);
    header("Location: https://ucp.fade.lv/");
    }
    function isLoggedIn() {
    if (isset($_SESSION["username"])) {
    return true;
    } else {
    return false;
    }
    }

    function serverData() {
        include 'db.php';
        $result = $conn->query("SELECT PlaceholerName, PlaceholerValue FROM serverdata"); 
        if ($result->num_rows > 0) {
            $data = array(); 
            $allEmpty = true; // track variables
            while ($row = $result->fetch_assoc()) {
                $placeholderName = $row['PlaceholerName'];
                $placeholderValue = $row['PlaceholerValue'];
                $data[$placeholderName] = $placeholderValue;
                
                // check for spaces
                if ($placeholderValue !== " ") {
                    $allEmpty = false;
                }
            }
            
            if ($allEmpty) {
                return "offline";
            }
            
            return $data;
        } else {
            return "offline"; // no rows = offline
        }
    }
    
    
    
    
function authmeUserData($user) {
    include 'authdb.php';
    $result = $conn->query(
        "SELECT lastlogin,regdate,ip, (
            SELECT COUNT(*) FROM authme ) AS COUNT FROM authme WHERE username = '$user'");
    $row = $result->fetch_assoc();
    $timestamp = (int)($row['lastlogin'] / 1000); // laiks kad pedejo reizi bija successful login
    $date = date('Y-m-d\ H:i', $timestamp);
    $timestamp = (int)($row['regdate'] / 1000); // laiks kad veica register authme
    $regdate = date('Y-m-d\ H:i', $timestamp);
    return array($date, $regdate, $row['ip'],$row['COUNT']);
}
function publicProfileData($user) {
    include 'authdb.php';
    $sqlusername = mysqli_real_escape_string($conn, $user);
    $authResult = $conn->query(
        "SELECT lastlogin,regdate FROM authme WHERE username = '$sqlusername'");
    $authRow = $authResult->fetch_assoc();
    $timestamp = (int)($authRow['lastlogin'] / 1000);
    $date = date('Y-m-d\ H:i', $timestamp);
    $timestamp = (int)($authRow['regdate'] / 1000); 
    $regdate = date('Y-m-d\ H:i', $timestamp);

    include 'db.php';
    $sqlusername = mysqli_real_escape_string($conn, $user);
    $webResult = $conn->query(
        "SELECT lastloginweb,coins,customskin FROM users WHERE mcusername = '$sqlusername'");
    $webRow = $webResult->fetch_assoc();
    return array($date, $regdate, $webRow['coins'], $webRow['lastloginweb'], $webRow['customskin']);
}
function offlineUUID($user) {
    $data = hex2bin(md5("OfflinePlayer:" . $user));
    $data[6] = chr(ord($data[6]) & 0x0f | 0x30);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return createJavaUuid(bin2hex($data));
}
function createJavaUuid($striped) {
    $components = array(
        substr($striped, 0, 8),
        substr($striped, 8, 4),
        substr($striped, 12, 4),
        substr($striped, 16, 4),
        substr($striped, 20),
    );
    return implode('-', $components);
}
function getCoins($token) {
    include 'db.php';
    $realname = mysqli_real_escape_string($conn, $token);
    $sql = "SELECT coins FROM users WHERE token = '$realname'";
    $result = $conn->query($sql);
    $userdata = $result->fetch_assoc();
    return $userdata["coins"];
}
include 'db.php';
if (isset($_POST["getCoinsUsername"])) {
    $getCoins = mysqli_real_escape_string($conn, $_POST['getCoinsUsername']);
    $sql = "SELECT token,coins FROM users WHERE token = '$getCoins'";
    $result = $conn->query($sql);
    $userdata = $result->fetch_assoc();
    $coins = $userdata["coins"];
    echo "$coins";
}
?>