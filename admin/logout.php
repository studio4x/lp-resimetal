<?php
require_once('../includes/config.php');
if (session_status() === PHP_SESSION_NONE) session_start();
unset($_SESSION[ADMIN_SESSION_NAME]);
session_destroy();
header("Location: login.php");
exit();
?>
