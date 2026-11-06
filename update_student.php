<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';
include 'log_activity.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id        = $_POST['id'];
    $reg_no    = trim($_POST['reg_no']);
    $full_name = trim($_POST['full_name']);
    $course    = trim($_POST['course']);
    $gender    = trim($_POST['gender']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);

    // Handle photo upload
    $photo = $_FILES['photo']['name'];
    $tmp   = $_FILES['photo']['tmp_name'];

    if ($photo != '') {
        $type = pathinfo($photo, PATHINFO_EXTENSION);
        if ($type != 'jpg' && $type != 'jpeg' && $type != 'png') {
            die("Invalid file type.");
        }
        if ($_FILES['photo']['size'] > 2000000) {
            die("File too large.");
        }
        move_uploaded_file($tmp, "uploads/" . $photo);

        // Update with new photo — prepared statement
        $stmt = $conn->prepare("UPDATE students 
            SET reg_no=?, full_name=?, course=?, gender=?, email=?, phone=?, photo=?
            WHERE id=?");
        $stmt->bind_param("sssssssi", $reg_no, $full_name, $course, $gender, $email, $phone, $photo, $id);

    } else {
        // Keep existing photo — prepared statement
        $stmt = $conn->prepare("UPDATE students 
            SET reg_no=?, full_name=?, course=?, gender=?, email=?, phone=?
            WHERE id=?");
        $stmt->bind_param("ssssssi", $reg_no, $full_name, $course, $gender, $email, $phone, $id);
    }

    if ($stmt->execute()) {
    log_activity($conn, $_SESSION['user'], "Edited Student ID: " . $id);
    header("Location: view_students.php");
    exit();
    }  else {
        echo "Error: " . $stmt->error;
    }

} else {
    header("Location: view_students.php");
}
?>