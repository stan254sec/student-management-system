<?php
session_start();
include "db.php";
include 'log_activity.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user'] = $user['full_name'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            log_activity($conn, $user['full_name'], "Logged In");
            header("Location: view_students.php");
            exit();

        } else {
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Login Error</title>
                <link rel='stylesheet' href='style.css'>
            </head>
            <body>
            <div class='auth-wrapper'>
                <div class='auth-box'>
                    <div class='auth-header'>
                        <h2>College System</h2>
                    </div>
                    <div class='error'>
                        ❌ Incorrect Password
                    </div>
                    <p class='auth-link'>
                        <a href='login.php'>Go back</a>
                    </p>
                </div>
            </div>
            </body>
            </html>";
        }

    } else {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Login Error</title>
            <link rel='stylesheet' href='style.css'>
        </head>
        <body>
        <div class='auth-wrapper'>
            <div class='auth-box'>
                <div class='auth-header'>
                    <h2>College System</h2>
                </div>
                <div class='error'>
                    ❌ User Not Found
                </div>
                <p class='auth-link'>
                    <a href='login.php'>Go back</a>
                </p>
            </div>
        </div>
        </body>
        </html>";
    }

} else {
    header("Location: login.php");
}
?>