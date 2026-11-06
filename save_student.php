<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    die("<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
    <div class='auth-wrapper'>
        <div class='auth-box'>
            <div class='error'>❌ Access Denied. Administrators only.</div>
            <p class='auth-link'><a href='view_students.php'>Go back</a></p>
        </div>
    </div>
    </body>
    </html>");
}

include 'db.php';
include 'log_activity.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $reg_no    = trim($_POST['reg_no']);
    $full_name = trim($_POST['full_name']);
    $course    = trim($_POST['course']);
    $gender    = trim($_POST['gender']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);

    $errors = [];

    if (empty($full_name)) $errors[] = "❌ Full name cannot be empty.";
    if (empty($reg_no)) $errors[] = "❌ Registration number cannot be empty.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "❌ Invalid email address format.";
    if (!preg_match('/^(07|01)[0-9]{8}$/', $phone)) $errors[] = "❌ Invalid phone number.";
    if (empty($course)) $errors[] = "❌ Please select a course.";

    // Check duplicate reg_no — prepared statement
    $check = $conn->prepare("SELECT * FROM students WHERE reg_no=?");
    $check->bind_param("s", $reg_no);
    $check->execute();
    $check_result = $check->get_result();
    if ($check_result->num_rows > 0) $errors[] = "❌ Registration number already exists.";

    if (!empty($errors)) {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Validation Error</title>
            <link rel='stylesheet' href='style.css'>
        </head>
        <body>";
        include 'menu.php';
        echo "<div class='content'><div class='container'><h2>Validation Errors</h2>";
        foreach ($errors as $error) {
            echo "<div class='error'>$error</div>";
        }
        echo "<br><a href='add_student.php' class='btn-save' 
              style='display:block; text-align:center; padding:10px; text-decoration:none;'>
              Go Back</a></div></div></body></html>";
        exit();
    }

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
    } else {
        $photo = 'no_photo.png';
    }

    // Insert — prepared statement
    $stmt = $conn->prepare("INSERT INTO students 
        (reg_no, full_name, course, gender, email, phone, photo) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $reg_no, $full_name, $course, $gender, $email, $phone, $photo);

    if ($stmt->execute()) {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Success</title>
            <link rel='stylesheet' href='style.css'>
        </head>
        <body>";
        include 'menu.php';
        log_activity($conn, $_SESSION['user'], "Added Student: " . $full_name);
        echo "<div class='content'><div class='container'>
            <div class='success'>✅ Student added successfully!</div>
            <br>
            <a href='add_student.php' class='btn-save' style='display:block; text-align:center; padding:10px; text-decoration:none;'>Add Another Student</a>
            <br>
            <a href='view_students.php' style='display:block; text-align:center;'>View All Students</a>
        </div></div></body></html>";
    } else {
        echo "Error: " . $stmt->error;
    }

} else {
    header("Location: add_student.php");
}
?>