<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-box">

        <div class="auth-header">
            <h2>College System</h2>
            <p>Sign in to your account</p>
        </div>

        <form method="POST" action="authenticate.php">

            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <button type="submit" class="btn-save">Login</button>

        </form>

        <p class="auth-link">
            Don't have an account?
            <a href="register.php">Register here</a>
        </p>

    </div>
</div>

</body>
</html>