<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '/var/www/html/db.php';
if (isset($_GET["id"])) {
    $getGame = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM games WHERE ID = $getGame";
    $result = $conn->query($sql);
    $gameData = $result->fetch_assoc();
    
    if ($gameData) {
        echo "Game ID: " . $gameData['ID'] . "<br>";
        echo "Target: " . $gameData['target'] . "<br>";
        echo "Server Hash: " . $gameData['serverhash'] . "<br>";
        echo "Client Seed: " . $gameData['clientseed'] . "<br>";
        echo "Game Date: " . $gameData['game_date'] . "<br>";


// ...

$sql = "SELECT DISTINCT * FROM bets WHERE gameID = $getGame";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Bets: ($result->num_rows total)</h3>";

    while ($row = $result->fetch_assoc()) {
        echo "User: " . $row['user'] . "<br>";
        echo "Target Bet: " . $row['target'] . "x<br>";
        echo "Amount Bet: " . $row['amount'] . "<br>";
        echo "<br>";
    }
} else {
    echo "<br><h3>No bets found for the specified game ID.</h3>";
}

// ...

    } else {
        echo "No data found for the specified game ID.";
    }
}


?>