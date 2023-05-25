<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Thedudeguy\Rcon;
session_start();
$allowed_types = array('jpg', 'jpeg', 'png');
$max_size = 1024 * 7; // 7 kb
$max_width = 64;
$max_height = 64;
$upload_dir = 'uploads/';

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
    mcRcon($_SESSION['username'],$new_file_name);
    echo "Skins tika samaints";
  } else if (!in_array($file_ext, $allowed_types)) {
    echo "Atļautie faila tipi: .jpg vai .png";
  } else if ($file_size >= $max_size) {
    echo "Faila lielums nevar pārsniegt 7KB";
  } else if ($width >= $max_width || $height >= $max_height) {
      echo "Faila izmērs nevar pārsniegt 64x64";
  } else {
    echo "Sazinies ar mājaslapas administrātoru jex#0001";
  } 
}
}


function mcRcon($username,$file) {
// Load the RCON library
require_once('rcon.php');

$host = getenv('HOST_IP');
$port = getenv('HOST_PORT');
$password = getenv('RCON_PASSWORD');
$timeout = 3;
$link = "https://ucp.fade.lv/dashboard/skins/uploads/$file";

$rcon = new Rcon($host, $port, $password, $timeout);

// Send an RCON command
if ($rcon->connect())
{
    $message = "skin set $username $link";
    $rcon->sendCommand($message);
    $rcon->disconnect();
}
}
?>
