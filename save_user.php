<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $full_name = $_POST['full_name'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];
    $role      = $_POST['role'];

    // Check passwords match
    if ($password != $confirm) {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error</title>
            <link rel='stylesheet' href='style.css'>
        </head>
        <body>
        <div class='auth-wrapper'>
            <div class='auth-box'>
                <div class='auth-header'>
                    <h2>College System</h2>
                </div>
                <div class='error'>❌ Passwords do not match</div>
                <p class='auth-link'><a href='register.php'>Go back</a></p>
            </div>
        </div>
        </body>
        </html>";
        exit();
    }

    // Check duplicate email — prepared statement
    $check = $conn->prepare("SELECT * FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error</title>
            <link rel='stylesheet' href='style.css'>
        </head>
        <body>
        <div class='auth-wrapper'>
            <div class='auth-box'>
                <div class='auth-header'>
                    <h2>College System</h2>
                </div>
                <div class='error'>❌ Email already registered</div>
                <p class='auth-link'><a href='register.php'>Go back</a></p>
            </div>
        </div>
        </body>
        </html>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert — prepared statement
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Registration Successful</title>
            <link rel='stylesheet' href='style.css'>
        </head>
        <body>
        <div class='auth-wrapper'>
            <div class='auth-box'>
                <div class='auth-header'>
                    <h2>College System</h2>
                </div>
                <div class='success'>✅ Registration Successful!</div>
                <p class='auth-link'><a href='login.php'>Click here to Login</a></p>
            </div>
        </div>
        </body>
        </html>";
    } else {
        echo "Error: " . $stmt->error;
    }

} else {
    header("Location: register.php");
}
?>