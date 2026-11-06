<?php ?>

<div class="topbar">
    <div class="topbar-left">
        <a href="view_students.php">Home</a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
        <a href="add_student.php">Add Student</a>
        <a href="logs.php">📋 Logs</a>
        <?php } ?>

        <a href="view_students.php">View Students</a>
    </div>
    <div class="topbar-right">
        <span>Welcome, <?php echo $_SESSION['user']; ?> 
        | Role: <?php echo ucfirst($_SESSION['role']); ?></span>
        <a href="change_password.php" style="color:rgba(255,255,255,0.85); padding:7px 14px; border-radius:5px;">🔑 Change Password</a>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</div>