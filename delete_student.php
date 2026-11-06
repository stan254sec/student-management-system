<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Admin only
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
            <div class='error'>
                ❌ Access Denied. Administrators only.
            </div>
            <p class='auth-link'>
                <a href='view_students.php'>Go back</a>
            </p>
        </div>
    </div>
    </body>
    </html>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Top Navigation Bar -->
<?php include 'menu.php'; ?>

<div class="content">
<div class="container">
    <h2>Delete Student</h2>

    <?php
    include 'db.php';
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    ?>

    <p>Are you sure you want to delete this student?</p>

    <table>
        <tr>
            <th>ID</th>
            <th>Reg No</th>
            <th>Full Name</th>
            <th>Course</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
            <td><?php echo htmlspecialchars($row['course']); ?></td>
        </tr>
    </table>
    <br>
    <a href="confirm_delete.php?id=<?php echo $id; ?>"
       onclick="return confirm('Are you sure you want to delete this student?')"
       class="btn-save">Yes, Delete</a>
    &nbsp;&nbsp;
    <a href="view_students.php" class="btn-cancel">Cancel</a>
</div>
</div>

</body>
</html>