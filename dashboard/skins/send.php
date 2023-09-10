<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include the RCON library
require_once('Rcon.php');
// Define RCON server details
$host = '176.9.21.19';
$port = 8175; // Default RCON port
$password = 'RCONPASS';
$timeout = 5;
use Thedudeguy\Rcon;
try {
    // Create an RCON instance and establish a connection
    $rcon = new Rcon($host, $port, $password, $timeout);

    if ($rcon->connect()) {
        $command = `skin set $username $link`;
        $response = $rcon->sendCommand($command);
        echo "Response from server: $response";
        $rcon->disconnect();
    } else {
        echo "Failed to connect to RCON server.";
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

?>
