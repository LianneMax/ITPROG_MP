<!-- 
    admin_create_offerings.php
    Create course offerings via uploading well-formed xml files

    Last updated: November 30, 2024 | 3:00AM by Lianne Balbastro

    TODO: 
        **Polish SQL Error handling**

        PENDING: Manual Input Option
        DONE: View current offerings feature
        DONE: Fix position of OFFERINGS table
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

// Handle Manual Offering Addition
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_offering"])) {
    $offering_code = $conn->real_escape_string($_POST["offering_code"]);
    $course_code = $conn->real_escape_string($_POST["course_code"]);
    $section = $conn->real_escape_string($_POST["section"]);
    $class_days = isset($_POST["class_days"]) ? implode("", $_POST["class_days"]) : ""; // Concatenate selected days
    $class_start_time = $conn->real_escape_string($_POST["class_start_time"]);
    $class_end_time = $conn->real_escape_string($_POST["class_end_time"]);
    $enroll_cap = (int)$_POST["enroll_cap"];
    $professor = $conn->real_escape_string($_POST["professor"]);
    $room = $conn->real_escape_string($_POST["room"]);

    // Check for duplicate offering code
    $checkDuplicateCodeQuery = "SELECT offering_code FROM section_offerings WHERE offering_code = '$offering_code'";
    $duplicateCodeResult = $conn->query($checkDuplicateCodeQuery);

    if ($duplicateCodeResult && $duplicateCodeResult->num_rows > 0) {
        $error_messages[] = "Duplicate entry: Offering code '$offering_code' already exists.";
    } else {
        // Check for duplicate section globally (not tied to course)
        $checkDuplicateSectionQuery = "SELECT section FROM section_offerings WHERE section = '$section'";
        $duplicateSectionResult = $conn->query($checkDuplicateSectionQuery);

        if ($duplicateSectionResult && $duplicateSectionResult->num_rows > 0) {
            $error_messages[] = "Duplicate section: Section '$section' already exists and cannot be reused.";
        } else {
            // Insert into database
            $insertQuery = "INSERT INTO section_offerings (offering_code, course_code, section, class_days, class_start_time, class_end_time, enroll_cap, professor, room) 
                            VALUES ('$offering_code', '$course_code', '$section', '$class_days', '$class_start_time', '$class_end_time', $enroll_cap, '$professor', '$room')";

            if ($conn->query($insertQuery)) {
                $success_messages[] = "Offering '$offering_code' added successfully!";
            } else {
                $error_messages[] = "Error adding offering '$offering_code': " . $conn->error;
            }
        }
    }
}
?>

<html>
<head>
    <title>Admin | Manage Offerings</title>
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
        <h2 class="title-header">Manage Offerings</h2>
        <div class="separator"></div>

        <!-- Display Messages -->
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

        <!-- XML Upload -->
        <form action="admin_process_offerings.php" method="post" enctype="multipart/form-data">
            <div class="file-input-container">
                <label for="xml">XML File:</label>
                <input type="file" id="xml" name="xml" required>
                <button type="submit" class="main-button admin-button">Upload</button>
            </div>
        </form>

        <!-- Manual Add Offering -->
        <div class="offerings-container">
            <div class="form-container">
                <form method="POST" action="">
                    <h4>Add Offering</h4>

                    <label for="offering_code">Offering Code:</label>
                    <input type="text" id="offering_code" name="offering_code" pattern="\d+" title="Must be numeric" required>

                    <label for="course_code">Course Code:</label>
                    <select id="course_code" name="course_code" required>
                        <option value="">Select a Course</option>
                        <?php
                        $sqlCourses = "SELECT course_code FROM courses";
                        $resultCourses = $conn->query($sqlCourses);
                        while ($course = $resultCourses->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($course['course_code']) . "'>" . htmlspecialchars($course['course_code']) . "</option>";
                        }
                        ?>
                    </select>

                    <label for="section">Section:</label>
                    <input type="text" id="section" name="section" required>

                    <label for="class_days">Class Days:</label>
                    <div class="checkbox-container">
                        <input type="checkbox" name="class_days[]" value="M"> M
                        <input type="checkbox" name="class_days[]" value="T"> T
                        <input type="checkbox" name="class_days[]" value="W"> W
                        <input type="checkbox" name="class_days[]" value="H"> H
                        <input type="checkbox" name="class_days[]" value="F"> F
                        <input type="checkbox" name="class_days[]" value="S"> S
                    </div>

                    <label for="class_start_time">Start Time:</label>
                    <input type="time" id="class_start_time" name="class_start_time" required>

                    <label for="class_end_time">End Time:</label>
                    <input type="time" id="class_end_time" name="class_end_time" required>

                    <label for="enroll_cap">Enrollment Capacity:</label>
                    <input type="number" id="enroll_cap" name="enroll_cap" min="1" required>

                    <label for="professor">Professor:</label>
                    <select id="professor" name="professor" required>
                        <?php
                        $sqlProfessors = "SELECT prof_fullname FROM professors";
                        $resultProfessors = $conn->query($sqlProfessors);
                        while ($professor = $resultProfessors->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($professor['prof_fullname']) . "'>" . htmlspecialchars($professor['prof_fullname']) . "</option>";
                        }
                        ?>
                    </select>

                    <label for="room">Room:</label>
                    <input type="text" id="room" name="room" required>

                    <button type="submit" name="save_offering" class="main-button admin-button" style="grid-column: span 2;">Add Offering</button>
                </form>
            </div>
            <!-- Current Offerings Table -->
            <div class="table-container">
                <h4>Current Offerings</h4>
                <?php
                displayOfferings($conn);
                ?>
            </div>
        </div>
    </div>
</div>
<script src="../includes/main.js"></script>
</body>
</html>




















