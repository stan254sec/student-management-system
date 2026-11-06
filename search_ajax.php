<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo "Unauthorized";
    exit();
}

include 'db.php';

$search = isset($_POST['search']) ? trim($_POST['search']) : '';

if ($search == '') {
    echo "<p style='color:#888;'>Start typing to search students...</p>";
    exit();
}

$search_param = "%$search%";

$stmt = $conn->prepare("SELECT * FROM students 
        WHERE full_name LIKE ? 
        OR course LIKE ? 
        OR reg_no LIKE ?");
$stmt->bind_param("sss", $search_param, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p style='color:#e74c3c;'>No students found matching <strong>" . htmlspecialchars($search) . "</strong></p>";
    exit();
}
?>

<p style="color:#555; margin-bottom:10px;">
    Found <strong><?php echo $result->num_rows; ?></strong> 
    result(s) for: <strong><?php echo htmlspecialchars($search); ?></strong>
</p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Reg No</th>
            <th>Full Name</th>
            <th>Course</th>
            <th>Gender</th>
            <th>Email</th>
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <th>Edit</th>
                <th>Delete</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td>
                <?php if ($row['photo'] != '' && $row['photo'] != 'no_photo.png') { ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" 
                         width="50" height="50" style="border-radius:50%; object-fit:cover;">
                <?php } else { ?>
                    <span style="color:#999;">No Photo</span>
                <?php } ?>
            </td>
            <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
            <td><?php echo htmlspecialchars($row['course']); ?></td>
            <td><?php echo htmlspecialchars($row['gender']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <td>
                    <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
                </td>
                <td>
                    <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="delete">Delete</a>
                </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>