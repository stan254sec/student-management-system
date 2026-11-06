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
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Top Navigation Bar -->
<?php include 'menu.php'; ?>

<div class="content">
<div class="container">
    <h2>Edit Student Details</h2>
    <?php
    include 'db.php';
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    ?>
    <form method="POST" action="update_student.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        <label>Registration Number:</label>
        <input type="text" name="reg_no" value="<?php echo htmlspecialchars($row['reg_no']); ?>">

        <label>Full Name:</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($row['full_name']); ?>">

        <label>Course:</label>
        <input type="text" name="course" value="<?php echo htmlspecialchars($row['course']); ?>">

        <label>Gender:</label>
        <select name="gender">
            <option value="Male" <?php if($row['gender']=='Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if($row['gender']=='Female') echo 'selected'; ?>>Female</option>
        </select>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">

        <label>Current Photo:</label>
        <?php if ($row['photo'] != '' && $row['photo'] != 'no_photo.png') { ?>
            <img src="uploads/<?php echo $row['photo']; ?>" width="80" height="80" style="border-radius:50%; object-fit:cover; display:block; margin-bottom:8px;">
        <?php } else { ?>
            <span style="color:#999;">No photo</span><br><br>
        <?php } ?>

        <label>Change Photo:</label>
        <input type="file" name="photo">

        <button type="submit" class="btn-update">Update Student</button>
    </form>
    <a href="view_students.php" class="btn-cancel">Cancel</a>
</div>
</div>

</body>
</html>