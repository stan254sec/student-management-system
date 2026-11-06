<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include 'db.php';

// Search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Count total records
if ($search != '') {
    $search_param = "%$search%";
    $count_stmt = $conn->prepare("SELECT * FROM students 
                  WHERE full_name LIKE ? 
                  OR course LIKE ?
                  OR reg_no LIKE ?");
    $count_stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
} else {
    $count_result = mysqli_query($conn, "SELECT * FROM students");
}
$total_records = mysqli_num_rows($count_result);
$total_pages = ceil($total_records / $limit);

// Main query with pagination
if ($search != '') {
    $search_param = "%$search%";
    $stmt = $conn->prepare("SELECT * FROM students 
            WHERE full_name LIKE ? 
            OR course LIKE ?
            OR reg_no LIKE ?
            LIMIT ?, ?");
    $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $start, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $stmt = $conn->prepare("SELECT * FROM students LIMIT ?, ?");
    $stmt->bind_param("ii", $start, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
}
$count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Top Navigation Bar -->
    <?php include 'menu.php'; ?>

<div class="content">
    <h2>Registered Students</h2>
    <?php if ($_SESSION['role'] == 'admin') { ?>
        <a href="add_student.php" class="btn-add">+ Add New Student</a>
    <?php } ?>
    <br><br>

    <!-- AJAX Search Box -->
    <input type="text" id="searchBox" 
        placeholder="Search by name, course or reg no..." 
        style="padding:10px; width:350px; border:1px solid #ccc; border-radius:5px; font-size:15px;">
    <br><br>

    <div id="searchResults"></div>

    <!-- Search Results Info -->
    <?php if ($search != '') { ?>
        <p style="color:#555;">Found <strong><?php echo $total_records; ?></strong> result(s) for: <strong><?php echo $search; ?></strong></p>
    <?php } ?>

    <!-- Records Info -->
    <p style="color:#555;">
        Showing <strong><?php echo $start + 1; ?></strong> to 
        <strong><?php echo min($start + $limit, $total_records); ?></strong> of 
        <strong><?php echo $total_records; ?></strong> students
    </p>

    <table>
        <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Reg No</th>
            <th>Full Name</th>
            <th>Course</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
           <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td>
                <?php if ($row['photo'] != '' && $row['photo'] != 'no_photo.png') { ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" width="60" height="60" style="border-radius:50%; object-fit:cover;">
                <?php } else { ?>
                    <span style="color:#999;">No Photo</span>
                <?php } ?>
            </td>
            <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
            <td><?php echo htmlspecialchars($row['course']); ?></td>
            <td><?php echo htmlspecialchars($row['gender']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td>
                <?php if ($_SESSION['role'] == 'admin') { ?>
                    <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
                <?php } else { ?>
                    <span style="color:#999;">N/A</span>
                <?php } ?>
            </td>
            <td>
                <?php if ($_SESSION['role'] == 'admin') { ?>
                    <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="delete">Delete</a>
                <?php } else { ?>
                    <span style="color:#999;">N/A</span>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <!-- Pagination Links -->
    <div class="pagination">
        <?php if ($page > 1) { ?>
            <a href="view_students.php?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>">← Previous</a>
        <?php } ?>

        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="view_students.php?page=<?php echo $i; ?>&search=<?php echo $search; ?>"
               class="<?php if ($i == $page) echo 'active-page'; ?>">
                <?php echo $i; ?>
            </a>
        <?php } ?>

        <?php if ($page < $total_pages) { ?>
            <a href="view_students.php?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>">Next →</a>
        <?php } ?>
    </div>

</div>

<script src="script.js"></script>

</body>
</html>