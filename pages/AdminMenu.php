<?php
session_start();
include "../includes/dbconfig.php";

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch the admin's name from the database
$admin_id = $_SESSION['admin_id'];
$admin_name = "Admin"; // Default name in case query fails

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT CONCAT(admin_firstname, ' ', admin_lastname) AS fullname FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $admin_name = $row['fullname'];
}

$stmt->close();
$conn->close();
?>

<html>
<head>
    <title>Admin Menu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body style="background-image: url('../pages/Server.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

<!-- Main Container -->
<div class="enrollment_container">
    <!-- Admin Menu Header -->
    <h2 class="title-header">Welcome, <?php echo htmlspecialchars($admin_name); ?>!</h2>

    <!-- Separator -->
    <div class="separator"></div>

    <!-- Buttons for Admin Menu -->
    <div class="admenu-btn">
        <button class="main-button admin-button" onclick="window.location.href='admin_create_courses.php'">Create Courses</button>
        <button class="main-button admin-button" onclick="window.location.href='admin_create_offerings.php'">Create Offerings</button>
        <button class="main-button admin-button" onclick="window.location.href='admin_create_profs.php'">Create Profs</button>
        <button class="main-button admin-button" onclick="window.location.href='admin_summary_report.php'">Summary Report</button>
    </div>
</div>

<!-- Logout button -->
<i class="fas fa-sign-out-alt logout_icon" onclick="window.location.href='LogoutPage.php'" title="Logout" 
   style="font-size: 30px; position: absolute; bottom: 20px; right: 20px; color: #FFFFFF; cursor: pointer;"></i>

</body>
</html>

