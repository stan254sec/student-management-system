<?php
session_start();
include 'db.php';
include 'log_activity.php';

if (isset($_SESSION['user'])) {
    log_activity($conn, $_SESSION['user'], "Logged Out");
}

session_destroy();
header("Location: login.php");
exit();
?>