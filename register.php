<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-box">

        <div class="auth-header">
            <h2>College System</h2>
            <p>Create a new account</p>
        </div>

        <form method="POST" action="save_user.php">

            <label>Full Name:</label>
            <input type="text" name="full_name" placeholder="Enter your full name" required>

            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" placeholder="Confirm your password" required>

            <label>Role:</label>
            <select name="role">
                <option value="student">Student</option>
                <option value="admin">Administrator</option>
            </select>

            <button type="submit" class="btn-save">Register</button>

        </form>

        <p class="auth-link">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>

    </div>
</div>

</body>
</html>