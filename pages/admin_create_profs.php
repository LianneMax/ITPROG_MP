<?php
session_start();
include "../includes/dbconfig.php";
include "display_tables.php";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_messages = [];
$success_messages = [];
?>

<html>
<head>
    <title>Admin | Manage Professors</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navigation.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<?php include 'admin_SidebarTopPanel.php'; ?>

<!-- Main Content -->
<div class="content">
    <div class="AdminContainer">
        <h2 class="title-header">Manage Professors</h2>
        <div class="separator"></div>

        <!-- XML Upload Form -->
        <form action="admin_process_profs.php" method="post" enctype="multipart/form-data">
            <div class="file-input-container">
                <label for="xml">XML File:</label>
                <input type="file" id="xml" name="xml" required>
                <button type="submit" class="main-button admin-button">Upload</button>
            </div>
        </form>

        <!-- Manual Add -->
        <div class="offerings-container">   

        <!-- Add Professor Form -->
        <div class="form-container">
            <form method="POST" action="admin_process_profs.php">
                <h4>Add Professor</h4>
                <label for="prof_name">Professor Name:</label>
                <input type="text" id="prof_name" name="prof_name" placeholder="Enter Professor's Name" required>
                <button type="submit" name="add_prof" class="main-button admin-button" style="grid-column: span 2;">Add Professor</button>
            </form>
        </div>

        <!-- Professors Table -->
        <div class="table-container">
            <h4>Current Professors</h4>
            <?php
            // Use the displayProfs function to render the table
            displayProfs($conn);
            ?>
        </div>
    </div>
</div>

<script src="../includes/main.js"></script>
</body>
</html>


















