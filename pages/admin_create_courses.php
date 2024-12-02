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

// Handle XML upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["xml"])) {
    if ($_FILES["xml"]["error"] === UPLOAD_ERR_OK) {
        $mime_types = ["text/xml", "application/xml"];
        if (in_array($_FILES["xml"]["type"], $mime_types)) {
            $filepath = $_FILES["xml"]["tmp_name"];
            $xml = simplexml_load_file($filepath) or die("Error: Cannot create object!");

            foreach ($xml->course as $course) {
                $course_code = $conn->real_escape_string($course['course_code']);
                $course_title = $conn->real_escape_string($course->course_title);
                $units = (int)$course->units;
                $co_requisite = isset($course->co_requisite) ? $conn->real_escape_string($course->co_requisite) : null;
                $prerequisites = [];

                foreach ($course->prerequisite as $prereq) {
                    $prerequisites[] = $conn->real_escape_string($prereq);
                }

                try {
                    // Add course code to `course_codes` if not exists
                    $conn->query("INSERT IGNORE INTO course_codes (course_code) VALUES ('$course_code')");

                    // Insert course details
                    $conn->query("INSERT INTO courses (course_code, course_title, units, co_requisite) 
                                  VALUES ('$course_code', '$course_title', $units, " . ($co_requisite ? "'$co_requisite'" : "NULL") . ")");

                    // Insert prerequisites
                    foreach ($prerequisites as $prerequisite) {
                        $conn->query("INSERT INTO prerequisites (course_code, prerequisite) 
                                      VALUES ('$course_code', '$prerequisite')");
                    }

                    $success_messages[] = "Course '$course_code' and its details were added successfully!";
                } catch (mysqli_sql_exception $e) {
                    if ($e->getCode() === 1062) {
                        $error_messages[] = "Duplicate entry: Course code '$course_code' already exists.";
                    } else {
                        $error_messages[] = "Error adding course '$course_code': " . $e->getMessage();
                    }
                }
            }
        } else {
            $error_messages[] = "Invalid file type. Only XML files are allowed.";
        }
    } else {
        $error_messages[] = "Error uploading file. Please try again.";
    }
}

// Handle manual course addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_course'])) {
    $course_code = htmlspecialchars($_POST['course_code']);
    $course_title = htmlspecialchars($_POST['course_title']);
    $units = (int)$_POST['units'];
    $co_requisite = htmlspecialchars($_POST['co_requisite']);
    $prerequisites = isset($_POST['prerequisites']) ? $_POST['prerequisites'] : [];

    try {
        // Check if course code exists
        $checkQuery = "SELECT * FROM course_codes WHERE course_code = '$course_code'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows === 0) {
            $conn->query("INSERT INTO course_codes (course_code) VALUES ('$course_code')");
        }

        $conn->query("INSERT INTO courses (course_code, course_title, units, co_requisite) 
                      VALUES ('$course_code', '$course_title', $units, " . ($co_requisite ? "'$co_requisite'" : "NULL") . ")");

        foreach ($prerequisites as $prerequisite) {
            $conn->query("INSERT INTO prerequisites (course_code, prerequisite) 
                          VALUES ('$course_code', '$prerequisite')");
        }

        $success_messages[] = "New course '$course_code' added successfully.";
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) {
            $error_messages[] = "Duplicate entry: The course code '$course_code' already exists.";
        } else {
            $error_messages[] = "Error: Could not add course '$course_code' - " . $e->getMessage();
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

        <?php if (!empty($error_messages)): ?>
            <div class="error-messages"><?php foreach ($error_messages as $error): ?><p class="error-message"><?php echo $error; ?></p><?php endforeach; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_messages)): ?>
            <div class="success-messages"><?php foreach ($success_messages as $success): ?><p class="success-message"><?php echo $success; ?></p><?php endforeach; ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="file-input-container">
                <label for="xml">XML File:</label>
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












    








