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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="content">
<div class="container">
    <h2>Student Registration</h2>

    <form method="POST" action="save_student.php"
          enctype="multipart/form-data"
          onsubmit="return validateForm()">

        <label>Registration Number:</label>
        <input type="text" name="reg_no" id="reg_no" placeholder="e.g. SCCJ/001/2024">

        <label>Full Name:</label>
        <input type="text" name="full_name" id="full_name" placeholder="Enter full name">

        <label>Course:</label>
        <input type="text" name="course" id="course" placeholder="Enter course">

        <label>Gender:</label>
        <select name="gender" id="gender">
            <option value="">-- Select Gender --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label>Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter email">

        <label>Phone Number:</label>
        <input type="text" name="phone" id="phone" placeholder="e.g. 0712345678">

        <label>Student Photo:</label>
        <input type="file" name="photo" id="photo" accept=".jpg,.jpeg,.png">

        <button type="submit" class="btn-save">Save Student</button>
    </form>
    <a href="view_students.php">View All Students</a>
</div>
</div>

<script>
function showError(inputId, message) {
    // Remove existing error
    const existing = document.getElementById(inputId + '_error');
    if (existing) existing.remove();

    // Create error message below input
    const input = document.getElementById(inputId);
    const errorDiv = document.createElement('div');
    errorDiv.id = inputId + '_error';
    errorDiv.style.color = '#e74c3c';
    errorDiv.style.fontSize = '0.85rem';
    errorDiv.style.marginTop = '-10px';
    errorDiv.style.marginBottom = '8px';
    errorDiv.innerHTML = '❌ ' + message;
    input.after(errorDiv);

    // Highlight input in red
    input.style.borderColor = '#e74c3c';
}

function clearError(inputId) {
    const existing = document.getElementById(inputId + '_error');
    if (existing) existing.remove();
    document.getElementById(inputId).style.borderColor = '#ccc';
}

function validateForm() {

    // Clear all previous errors
    ['reg_no', 'full_name', 'course', 'gender', 'email', 'phone'].forEach(clearError);

    let hasErrors = false;

    // Get values
    const reg_no    = document.getElementById('reg_no').value.trim();
    const full_name = document.getElementById('full_name').value.trim();
    const course    = document.getElementById('course').value.trim();
    const gender    = document.getElementById('gender').value;
    const email     = document.getElementById('email').value.trim();
    const phone     = document.getElementById('phone').value.trim();

    // 1. Check registration number
    if (reg_no === '') {
        showError('reg_no', 'Please enter the registration number.');
        hasErrors = true;
    }

    // 2. Check full name
    if (full_name === '') {
        showError('full_name', 'Please enter the student full name.');
        hasErrors = true;
    }

    // 3. Check course
    if (course === '') {
        showError('course', 'Please enter the course name.');
        hasErrors = true;
    }

    // 4. Check gender
    if (gender === '') {
        showError('gender', 'Please select a gender.');
        hasErrors = true;
    }

    // 5. Validate email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
        showError('email', 'Please enter an email address.');
        hasErrors = true;
    } else if (!emailPattern.test(email)) {
        showError('email', 'Please enter a valid email address.');
        hasErrors = true;
    }

    // 6. Validate phone
    const phonePattern = /^(07|01)[0-9]{8}$/;
    if (phone === '') {
        showError('phone', 'Please enter a phone number.');
        hasErrors = true;
    } else if (!phonePattern.test(phone)) {
        showError('phone', 'Phone must be 10 digits starting with 07 or 01.');
        hasErrors = true;
    }

    // Prevent submission if errors
    if (hasErrors) {
        return false;
    }

    return true;
}

// Clear error when user starts typing
document.addEventListener('DOMContentLoaded', function () {
    ['reg_no', 'full_name', 'course', 'email', 'phone'].forEach(function (id) {
        document.getElementById(id).addEventListener('input', function () {
            clearError(id);
        });
    });

    document.getElementById('gender').addEventListener('change', function () {
        clearError('gender');
    });
});
</script>

</body>
</html>