<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
// remove all session variables
session_unset();

// destroy the session
session_destroy();
setcookie("fadesession","", time()-3600, "/");
unset($_COOKIE['fadesession']);
header("Location: https://ucp.fade.lv/");
?>

</body>
</html>

