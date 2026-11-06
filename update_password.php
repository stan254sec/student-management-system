<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $old_password     = $_POST['old_password'];
    $new_password     = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id          = $_SESSION['user_id'];

    // Check new passwords match
    if ($new_password != $confirm_password) {
        header("Location: change_password.php?error=New passwords do not match");
        exit();
    }

    // Get current password from database — prepared statement
    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();

    // Verify old password
    if (!password_verify($old_password, $user['password'])) {
        header("Location: change_password.php?error=Current password is incorrect");
        exit();
    }

    // Hash new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password — prepared statement
    $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $update->bind_param("si", $hashed_password, $user_id);

    if ($update->execute()) {
        header("Location: change_password.php?success=1");
        exit();
    } else {
        header("Location: change_password.php?error=Failed to update password");
        exit();
    }

} else {
    header("Location: change_password.php");
}
?>