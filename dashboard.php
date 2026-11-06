<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include 'db.php';

// Get stats
$total_students = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM students"));
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
$total_admins = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='admin'"));
$total_student_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='student'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SmartCollege System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="content">

    <h2>Dashboard</h2>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-number"><?php echo $total_students; ?></div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👤</div>
            <div class="stat-number"><?php echo $total_users; ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔑</div>
            <div class="stat-number"><?php echo $total_admins; ?></div>
            <div class="stat-label">Administrators</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🎓</div>
            <div class="stat-number"><?php echo $total_student_users; ?></div>
            <div class="stat-label">Student Users</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <?php if ($_SESSION['role'] == 'admin') { ?>
    <div class="quick-actions">
        <h3>Quick Actions</h3>
        <div class="action-buttons">
            <a href="add_student.php" class="action-btn blue">+ Add Student</a>
            <a href="view_students.php" class="action-btn green">👥 View Students</a>
            <a href="register.php" class="action-btn orange">👤 Add User</a>
        </div>
    </div>
    <?php } else { ?>
    <div class="quick-actions">
        <h3>Quick Actions</h3>
        <div class="action-buttons">
            <a href="view_students.php" class="action-btn green">👥 View Students</a>
        </div>
    </div>
    <?php } ?>

    <!-- Welcome Message -->
    <div style="background:white; border-radius:12px; padding:25px 30px; box-shadow:var(--shadow);">
        <h3 style="color:var(--primary); margin-bottom:10px;">
            Welcome back, <?php echo $_SESSION['user']; ?>! 👋
        </h3>
        <p style="color:var(--text-gray);">
            You are logged in as <strong><?php echo ucfirst($_SESSION['role']); ?></strong>.
            <?php if ($_SESSION['role'] == 'admin') { ?>
            You have full access to manage student records.
            <?php } else { ?>
            You can view student records.
            <?php } ?>
        </p>
    </div>

</div>

</body>
</html>