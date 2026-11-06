<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="content">
<div class="container">
    <h2>Change Password</h2>

    <?php
    // Show success or error message if redirected
    if (isset($_GET['success'])) {
        echo "<div class='success'>✅ Password changed successfully!</div>";
    }
    if (isset($_GET['error'])) {
        $error = htmlspecialchars($_GET['error']);
        echo "<div class='error'>❌ $error</div>";
    }
    ?>

    <form method="POST" action="update_password.php" onsubmit="return validatePassword()">

        <label>Current Password:</label>
        <input type="password" name="old_password" id="old_password" placeholder="Enter current password">

        <label>New Password:</label>
        <input type="password" name="new_password" id="new_password" placeholder="Enter new password">

        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password">

        <button type="submit" class="btn-save">Change Password</button>

    </form>
    <a href="view_students.php" class="btn-cancel">Cancel</a>
</div>
</div>

<script>
function showError(inputId, message) {
    const existing = document.getElementById(inputId + '_error');
    if (existing) existing.remove();

    const input = document.getElementById(inputId);
    const errorDiv = document.createElement('div');
    errorDiv.id = inputId + '_error';
    errorDiv.style.color = '#e74c3c';
    errorDiv.style.fontSize = '0.85rem';
    errorDiv.style.marginTop = '-10px';
    errorDiv.style.marginBottom = '8px';
    errorDiv.innerHTML = '❌ ' + message;
    input.after(errorDiv);
    input.style.borderColor = '#e74c3c';
}

function clearError(inputId) {
    const existing = document.getElementById(inputId + '_error');
    if (existing) existing.remove();
    document.getElementById(inputId).style.borderColor = '#ccc';
}

function validatePassword() {

    ['old_password', 'new_password', 'confirm_password'].forEach(clearError);

    let hasErrors = false;

    const old_password     = document.getElementById('old_password').value.trim();
    const new_password     = document.getElementById('new_password').value.trim();
    const confirm_password = document.getElementById('confirm_password').value.trim();

    // Check old password
    if (old_password === '') {
        showError('old_password', 'Please enter your current password.');
        hasErrors = true;
    }

    // Check new password
    if (new_password === '') {
        showError('new_password', 'Please enter a new password.');
        hasErrors = true;
    } else if (new_password.length < 4) {
        showError('new_password', 'New password must be at least 4 characters.');
        hasErrors = true;
    }

    // Check confirm password
    if (confirm_password === '') {
        showError('confirm_password', 'Please confirm your new password.');
        hasErrors = true;
    } else if (new_password !== confirm_password) {
        showError('confirm_password', 'Passwords do not match.');
        hasErrors = true;
    }

    if (hasErrors) return false;
    return true;
}

// Clear errors on input
document.addEventListener('DOMContentLoaded', function () {
    ['old_password', 'new_password', 'confirm_password'].forEach(function (id) {
        document.getElementById(id).addEventListener('input', function () {
            clearError(id);
        });
    });
});
</script>

</body>
</html>