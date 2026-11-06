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
    <title>Search Students</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="content">
    <h2>Live Student Search</h2>

    <input type="text" id="searchBox" 
           placeholder="Type to search by name, course or reg no..." 
           style="width:400px; padding:10px; border:1px solid #ccc; border-radius:5px; font-size:15px;">

    <br><br>

    <div id="searchResults">
        <p style="color:#888;">Start typing to search students...</p>
    </div>
</div>

<script src="script.js"></script>

</body>
</html>