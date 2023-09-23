<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Thedudeguy\Rcon;
require 'vendor/autoload.php';
require_once('Rcon.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
session_start();
$allowed_types = array('jpg', 'jpeg', 'png');
$max_size = 1024 * 7; // 7 kb
$max_width = 64;
$max_height = 64;
$upload_dir = 'uploads/';
ini_set('upload_tmp_dir', '/tmp');

if (isset($_FILES['file-input'])) {
  $file_name = $_FILES['file-input']['name'];
  $file_size = $_FILES['file-input']['size'];
  $file_tmp = $_FILES['file-input']['tmp_name'];
  $file_type = $_FILES['file-input']['type'];
  $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
  if (in_array($file_ext, $allowed_types) && $file_size <= $max_size) {
    list($width, $height) = getimagesize($file_tmp);
      if ($width <= $max_width && $height <= $max_height) {
    $new_file_name = $_SESSION['username'] . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;
    move_uploaded_file($file_tmp, $upload_path);
    updateProfile($_SESSION['username'],$file_ext);
    mcRcon($_SESSION['username'],$new_file_name);
       } else if (!in_array($file_ext, $allowed_types)) {
    echo "Atlautie faila tipi: .jpg vai .png";
  } else if ($file_size >= $max_size) {
    echo "Faila lielums nevar parsniegt 7KB";
  } else if ($width >= $max_width || $height >= $max_height) {
    echo "Faila izmers nevar parsniegt 64x64";
  }
} else echo "nederiga bilde"; }
function mcRcon($username,$file) {
  $host = '176.9.21.19';
  $port = 8175; 
  $password = 'RCONPASS';
  $timeout = 5;
$link = "https://ucp.fade.lv/dashboard/skins/uploads/$file";

try {
  // Create an RCON instance and establish a connection
  $rcon = new Rcon($host, $port, $password, $timeout);
  if ($rcon->connect()) {
    $command = "skin set $username $link";
      $response = $rcon->sendCommand($command);
      $rcon->disconnect();
  } else {
      echo "Failed to connect to server.";
  }
} catch (Exception $e) {
  echo "An error occurred: " . $e->getMessage();
}
}
function updateProfile($username,$link) {
  include '../../db.php';
  $sql = "UPDATE users SET customskin = 'set' WHERE mcusername = '$username'";
  $result = $conn->query($sql);
}
?>
