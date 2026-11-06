<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

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
            <div class='error'>❌ Access Denied. Administrators only.</div>
            <p class='auth-link'><a href='view_students.php'>Go back</a></p>
        </div>
    </div>
    </body>
    </html>");
}

include 'db.php';

$result = mysqli_query($conn, "SELECT * FROM logs ORDER BY date DESC, time DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="content">
    <h2>Activity Logs</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Activity</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['user']); ?></td>
                <td><?php echo htmlspecialchars($row['activity']); ?></td>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo htmlspecialchars($row['time']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>