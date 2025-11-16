<?php
session_start();
$_SESSION = [];
session_unset();
session_destroy();
setcookie('remember_username', '', time() - 3600, '/');
header('Location: login.php');
exit;
