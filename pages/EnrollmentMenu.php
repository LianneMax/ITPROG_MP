<?php
session_start();
include "../includes/dbconfig.php";

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch the student's name from the database
$student_id = $_SESSION['student_id'];
$student_name = "Student"; // Default name in case query fails

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT CONCAT(student_firstname, ' ', student_lastname) AS fullname FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_name = $row['fullname'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>ENROLLMENT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background-image: url('../pages/Client.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

    <div class="enrollment_container">
        <!-- Enrollment header with the student's name -->
        <h2 class="title-header">Welcome, <?php echo htmlspecialchars($student_name); ?>!</h2>

        <!-- Line below the Enrollment header -->
        <div class="separator"></div>

        <div class="enrollment-btn">
            <!-- Apply the 'main-button' class to style the buttons consistently -->
            <button class="main-button" onclick="window.location.href='add_class.php'">Add Classes</button>
            <button class="main-button" onclick="window.location.href='drop_class.php'">Remove Classes</button>
            <button class="main-button" onclick="window.location.href='CourseOfferings.php'">Course Offerings</button>
            <button class="main-button" onclick="window.location.href='ViewEAF.php'">View EAF</button>
        </div>
    </div>

    <!-- Logout button -->
    <i class="fas fa-sign-out-alt logout_icon" onclick="window.location.href='LogoutPage.php'" title="Logout" 
       style="font-size: 30px; position: absolute; bottom: 20px; right: 20px; color: #FFFFFF; cursor: pointer;"></i>

</body>
</html>
