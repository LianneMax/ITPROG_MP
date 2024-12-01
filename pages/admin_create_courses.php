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

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's the XML upload form

    // Check if it's the manual course input form
    if (isset($_POST['save_course'])) {
        // Process the manual input
        $course_code = htmlspecialchars($_POST['course_code']);
        $course_title = htmlspecialchars($_POST['course_title']);
        $units = (int)$_POST['units'];
        $co_requisite = htmlspecialchars($_POST['co_requisite']);
        $prerequisites = isset($_POST['prerequisites']) ? implode(",", $_POST['prerequisites']) : '';

        // Simplified query with sanitized inputs
        $sql = "INSERT INTO courses (course_code, course_title, units, co_requisite)
        VALUES ('$course_code', '$course_title', $units, '$co_requisite')";
        
        // Execute the query
        if ($conn->query($sql) === TRUE) {
        echo "New course added successfully.";
        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Fetch courses for dropdown options
$courseDropdownOptions = "";
$courseQuery = $conn->query("SELECT course_code FROM courses");
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
        <form action="admin_process_courses.php" method="post" enctype="multipart/form-data">
            <div class="file-input-container">
                <label for="xml">XML File:</label>
                <input type="file" id="xml" name="xml" required>
                <button type="submit" class="main-button admin-button">Upload</button>
            </div>
        </form>

        <!-- Manual Add/Edit/Delete -->
        <div class="offerings-container">

            <!-- Add/Edit Course Form -->
            <div class="form-container">
                <form method="POST" action="">
                    <!-- Submit to same file --> 
                    <h4>Add/Edit Course</h4>
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
                    <select id="prerequisites" name="prerequisites[]" multiple>
                        <?php echo $courseDropdownOptions; ?>
                    </select>

                    <button type="submit" name="save_course" class="main-button admin-button" style="grid-column: span 2;">Save Course</button>
                </f>
            </div>

            <!-- Existing Courses Table -->
            <div class="table-container">
                <h4>Current Courses</h4>
                <?php
                // Use the displayCourses function to render the table
                displayCourses($conn);
                ?>
            </div>
        </div>
    </div>
</div>
<script src="../includes/main.js"></script>
</body>
</html>








    








