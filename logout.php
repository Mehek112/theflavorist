<?php
session_start();
session_destroy();
setcookie("remember_email", "", time() - 3600, "/"); // Delete the cookie
header("Location: login.php");
exit();
?>
