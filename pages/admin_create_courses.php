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

// Handle manual add course within this file
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_course"])) {
    $course_code = $conn->real_escape_string(trim($_POST["course_code"]));
    $course_title = $conn->real_escape_string(trim($_POST["course_title"]));
    $units = (int)$_POST["units"];
    $co_requisite = $conn->real_escape_string(trim($_POST["co_requisite"]));
    $prerequisites = isset($_POST["prerequisites"]) ? $_POST["prerequisites"] : [];

    // Check for duplicate course code in `courses` table
    $checkDuplicateQuery = "SELECT course_code FROM courses WHERE course_code = '$course_code'";
    $duplicateResult = $conn->query($checkDuplicateQuery);

    if ($duplicateResult && $duplicateResult->num_rows > 0) {
        $error_messages[] = "Duplicate entry: Course code '$course_code' already exists.";
    } else {
        // Ensure the course_code exists in `course_codes` table
        $insertCourseCodeQuery = "INSERT IGNORE INTO course_codes (course_code) VALUES ('$course_code')";
        if ($conn->query($insertCourseCodeQuery)) {
            // Insert the course into `courses` table
            $insertCourseQuery = "INSERT INTO courses (course_code, course_title, units, co_requisite) 
                                  VALUES ('$course_code', '$course_title', $units, " . 
                                  (!empty($co_requisite) ? "'$co_requisite'" : "NULL") . ")";
            if ($conn->query($insertCourseQuery)) {
                // Insert prerequisites into `prerequisites` table
                foreach ($prerequisites as $prerequisite) {
                    $escapedPrerequisite = $conn->real_escape_string($prerequisite);
                    $insertPrerequisiteQuery = "INSERT INTO prerequisites (course_code, prerequisite) 
                                                VALUES ('$course_code', '$escapedPrerequisite')";
                    $conn->query($insertPrerequisiteQuery);
                }
                $success_messages[] = "Course '$course_code' added successfully!";
            } else {
                $error_messages[] = "Error adding course '$course_code': " . $conn->error;
            }
        } else {
            $error_messages[] = "Error ensuring course code '$course_code': " . $conn->error;
        }
    }
}

// Fetch courses for dropdown options
$courseDropdownOptions = "";
$courseQuery = $conn->query("SELECT course_code FROM course_codes");
while ($row = $courseQuery->fetch_assoc()) {
    $courseDropdownOptions .= "<option value='" . htmlspecialchars($row['course_code']) . "'>" . htmlspecialchars($row['course_code']) . "</option>";
}
?>


<html>
<head>
    <title>Admin | Manage Courses</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navigation.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<!-- Hamburger Menu Button -->
<div id="hamburger" class="hamburger">
    <i class="fas fa-bars"></i>
</div>

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

        <!-- Display Success and Error Messages -->
        <?php if (!empty($error_messages)): ?>
            <div class="error-messages">
                <?php foreach ($error_messages as $error): ?>
                    <p class="error-message"><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_messages)): ?>
            <div class="success-messages">
                <?php foreach ($success_messages as $success): ?>
                    <p class="success-message"><?php echo $success; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- XML Upload Form -->
        <form action="admin_process_courses.php" method="post" enctype="multipart/form-data">
            <div class="file-input-container">
                <label for="xml">Upload XML File:</label>
                <input type="file" id="xml" name="xml" required>
                <button type="submit" class="main-button admin-button">Upload</button>
            </div>
        </form>

        <!-- Manual Add Form -->
        <div class="offerings-container">
            <div class="form-container">
                <form method="POST" action="">
                    <h4>Add Course</h4>
                    <label for="course_code">Course Code:</label>
                    <input type="text" id="course_code" name="course_code" required>
                    <label for="course_title">Course Title:</label>
                    <input type="text" id="course_title" name="course_title" required>
                    <label for="units">Units:</label>
                    <select id="units" name="units" required>
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    <label for="co_requisite">Co-requisite:</label>
                    <select id="co_requisite" name="co_requisite">
                        <option value="">None</option>
                        <?php echo $courseDropdownOptions; ?>
                    </select>
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
                        <?php endwhile; else: ?>
                            <p style='color: red;'>No courses available to set as prerequisites.</p>
                        <?php endif; ?>
                    </div>
                    <button type="submit" name="save_course" class="main-button admin-button" style="grid-column: span 2;">Add Course</button>
                </form>
            </div>

            <!-- Display Current Courses -->
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



















    








