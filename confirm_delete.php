<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    die("Access Denied.");
}

include 'db.php';
include 'log_activity.php';

$id = $_GET['id'];

// Delete — prepared statement
$stmt = $conn->prepare("DELETE FROM students WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    log_activity($conn, $_SESSION['user'], "Deleted Student ID: " . $id);
    header("Location: view_students.php");
    exit();
}else {
    echo "Error: " . $stmt->error;
}
?>