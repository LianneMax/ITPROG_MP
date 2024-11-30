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

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch courses for manual course management dropdowns
$courseDropdownOptions = "";
$courseQuery = $conn->query("SELECT course_code FROM courses");
while ($row = $courseQuery->fetch_assoc()) {
    $courseDropdownOptions .= "<option value='" . htmlspecialchars($row['course_code']) . "'>" . htmlspecialchars($row['course_code']) . "</option>";
}

// Fetch existing courses for the table
$courses = [];
$courseTableQuery = $conn->query("
    SELECT c.course_code, c.course_title, c.units, c.co_requisite, GROUP_CONCAT(p.prerequisite) AS prerequisites 
    FROM courses c 
    LEFT JOIN prerequisites p ON c.course_code = p.course_code 
    GROUP BY c.course_code
");
while ($row = $courseTableQuery->fetch_assoc()) {
    $courses[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin | Add Courses</title>
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
        <span class="link-text">Create Profs</span>
    </button>
    <button class="sidebar-btn" onclick="window.location.href='admin_summary_report.php'">
        <i class="fas fa-chart-bar"></i>
        <span class="link-text">Summary Report</span>
    </button>
    <div class="separator" style="margin-top: 10px;"></div>
    <!-- Logout Button -->
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

        <!-- XML Upload -->
        <div class="file-input-container">
            <form action="admin_process_courses.php" method="post" enctype="multipart/form-data">
                <label for="xml">XML File:</label>
                <input type="file" id="xml" name="xml">
                <button type="submit" class="main-button admin-button">Upload</button>
            </form>
        </div>

        <!-- Manual Add/Edit/Delete -->
        <div class="table-container">

            <!-- Add/Edit Course Form -->
            <form method="POST" action="admin_process_courses.php">
                <h4>Add/Edit Course</h4>
                <label for="course_code">Course Code:</label>
                <input type="text" id="course_code" name="course_code" required><br><br>

                <label for="course_title">Course Title:</label>
                <input type="text" id="course_title" name="course_title" required><br><br>

                <label for="units">Units:</label>
                <select id="units" name="units" required>
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select><br><br>

                <label for="co_requisite">Co-requisite:</label>
                <select id="co_requisite" name="co_requisite">
                    <option value="">None</option>
                    <?php echo $courseDropdownOptions; ?>
                </select><br><br>

                <label for="prerequisites">Prerequisites:</label>
                <select id="prerequisites" name="prerequisites[]" multiple>
                    <?php echo $courseDropdownOptions; ?>
                </select><br><br>

                <button type="submit" name="save_course" class="main-button admin-button">Save Course</button>
            </form>

            <!-- Existing Courses Table -->
            <h4>Current Courses</h4>
            <table>
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Title</th>
                        <th>Units</th>
                        <th>Co-requisite</th>
                        <th>Prerequisites</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($courses) > 0): ?>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                                <td><?php echo htmlspecialchars($course['course_title']); ?></td>
                                <td><?php echo htmlspecialchars($course['units']); ?></td>
                                <td><?php echo htmlspecialchars($course['co_requisite'] ?: "None"); ?></td>
                                <td><?php echo htmlspecialchars($course['prerequisites'] ?: "None"); ?></td>
                                <td style="width: 200px;">
                                    <form method="POST" action="admin_process_courses.php" style="display: flex; justify-content: center; gap: 10px;">
                                    <button type="submit" name="edit_course" value="<?php echo htmlspecialchars($course['course_code']); ?>" class="main-button admin-button">Edit</button>
                                    <button type="submit" name="delete_course" value="<?php echo htmlspecialchars($course['course_code']); ?>" class="main-button admin-button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No courses found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

    








