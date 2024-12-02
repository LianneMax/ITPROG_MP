<!-- 
    admin_create_course.php
    Create courses via uploading well-formed xml files

    Last updated: November 30, 2024 | 2:11AM by Lianne Balbastro

    TODO: 
        **Polish SQL Error handling**

        PENDING: Manual Input Option
        DONE: View All Courses feature
        DONE: Fix position of courses table
 -->
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

// Fetch courses for dropdown options
$courseDropdownOptions = "";
$courseQuery = $conn->query("SELECT course_code FROM course_codes");
while ($row = $courseQuery->fetch_assoc()) {
    $courseDropdownOptions .= "<option value='" . htmlspecialchars($row['course_code']) . "'>" . htmlspecialchars($row['course_code']) . "</option>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin | Manage Courses</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navigation.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<!-- Sidebar -->
<div id="sidebar" class="sidebar admin-sidebar">
    <div class="separator" style="margin-top: 50px;"></div>
    <button class="sidebar-btn" onclick="window.location.href='admin_create_courses.php'">
        <i class="fas fa-book"></i>
        <span class="link-text">Create Courses</span>
    </button>
    <button class="sidebar-btn" onclick="window.location.href='admin_create_offerings.php'">
        <i class="fas fa-chalkboard-teacher"></i>
        <span class="link-text">Create Offerings</span>
    </button>
    <button class="sidebar-btn" onclick="window.location.href='admin_create_profs.php'">
        <i class="fas fa-user-tie"></i>
        <span class="link-text">Create Professors</span>
    </button>
    <button class="sidebar-btn" onclick="window.location.href='admin_summary_report.php'">
        <i class="fas fa-chart-bar"></i>
        <span class="link-text">Summary Report</span>
    </button>
    <div class="separator" style="margin-top: 10px;"></div>
    <button class="logout-btn" onclick="window.location.href='LogoutPage.php'">
        <i class="fas fa-sign-out-alt"></i>
    </button>
</div>

<!-- Top Panel -->
<div class="top-panel admin-top-panel">
    <h1 class="itmosys-header">ITmosys | Admin</h1>
</div>

<!-- Main Content -->
<div class="content">
    <div class="AdminContainer">
        <h2 class="title-header">Manage Courses</h2>
        <div class="separator"></div>

        <form action="admin_process_courses.php" method="post" enctype="multipart/form-data">
            <div class="file-input-container">
                <label for="xml">Upload XML File:</label>
                <input type="file" id="xml" name="xml" required>
                <button type="submit" class="main-button admin-button">Upload</button>
            </div>
        </form>

        <div class="offerings-container">
            <div class="form-container">
                <form method="POST" action="">
                    <h4>Add/Edit Course</h4>
                    <label for="course_code">Course Code:</label>
                    <input type="text" id="course_code" name="course_code" required>
                    <label for="course_title">Course Title:</label>
                    <input type="text" id="course_title" name="course_title" required>
                    <label for="units">Units:</label>
                    <select id="units" name="units" required><?php for ($i = 1; $i <= 6; $i++): ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?></select>
                    <label for="co_requisite">Co-requisite:</label>
                    <select id="co_requisite" name="co_requisite"><option value="">None</option><?php echo $courseDropdownOptions; ?></select>
                    <label for="prerequisites">Prerequisites:</label>
                    <div class="checkbox-container">
                        <?php
                        $prerequisiteQuery = $conn->query("SELECT course_code FROM course_codes");
                        if ($prerequisiteQuery->num_rows > 0):
                            while ($prerequisiteRow = $prerequisiteQuery->fetch_assoc()):
                                $prerequisiteCode = htmlspecialchars($prerequisiteRow['course_code']);
                        ?>
                            <div class="checkbox-item">
                                <input type="checkbox" id="prereq_<?php echo $prerequisiteCode; ?>" name="prerequisites[]" value="<?php echo $prerequisiteCode; ?>">
                                <label for="prereq_<?php echo $prerequisiteCode; ?>"><?php echo $prerequisiteCode; ?></label>
                            </div>
                        <?php
                            endwhile;
                        else:
                            echo "<p style='color: red;'>No courses available to set as prerequisites.</p>";
                        endif;
                        ?>
                    </div>
                    <button type="submit" name="save_course" class="main-button admin-button" style="grid-column: span 2;">Add Course</button>
                </form>
            </div>

            <div class="table-container">
                <h4>Current Courses</h4>
                <?php displayCourses($conn); ?>
            </div>
        </div>
    </div>
</div>

<script src="../includes/main.js"></script>
</body>
</html>













    








