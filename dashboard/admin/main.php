<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
</head>
<body>
    <div class="text"> Admin page </div>
    <form method="POST">
        <div class="input-container">
            <input type="text" name="username" id="username" placeholder="Type username..." required>
            <input type="submit" value="Search">
        </div>
    </form>
</body>
<?php
include("../../db.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // sanitize
    $username = mysqli_real_escape_string($conn, $_POST["username"]);

    $sql = "SELECT * FROM users WHERE mcusername = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // display
        echo "<div class='text'><p>Username: " . $user['mcusername'] . "<br>";
        echo "Banned: " . $user['banned'] . "<br>";
        echo "Coins: " . $user['coins'] . "<br>";

        echo "<form method='POST'>";
        echo "<input type='hidden' name='username' value='" . $user['mcusername'] . "'>";
        echo "Banned: ";
        
        echo "<input type='radio' name='banned' value='1' " . ($user['banned'] == 1 ? "checked" : "") . "> True ";
        echo "<input type='radio' name='banned' value='0' " . ($user['banned'] == 0 ? "checked" : "") . "> False<br>";
        
        echo "Update Coins: <input type='number' name='coins' step='any'value='" . $user['coins'] . "'><br>";
        echo "<input type='submit' value='Update'>";
        echo "</form></div>";
    } else {
        echo "<div class='text'>User not found.</div>";
    }

    // check form
    if (isset($_POST["username"]) && isset($_POST["banned"]) && isset($_POST["coins"])) {
        // Sanitize the input
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $banned = mysqli_real_escape_string($conn, $_POST["banned"]);
        $coins = mysqli_real_escape_string($conn, $_POST["coins"]);

        // update sql
        $updateSql = "UPDATE users SET banned = '$banned', coins = '$coins' WHERE mcusername = '$username'";
        if (mysqli_query($conn, $updateSql)) {
            echo "<div class='text'>User data updated successfully.</div>";
        } else {
            echo "Error updating user data: " . mysqli_error($conn);
        }
    }
}
?>
</html>
