<?php
session_start();
include "../includes/dbconfig.php";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_messages = [];
$success_messages = [];
$uploadedTable = ""; // Store the uploaded table HTML

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle XML Upload
    if (isset($_FILES["xml"])) {
        if ($_FILES["xml"]["error"] === UPLOAD_ERR_OK) {
            $mime_types = ["text/xml", "application/xml"];
            if (in_array($_FILES["xml"]["type"], $mime_types)) {
                $filepath = $_FILES["xml"]["tmp_name"];
                $xml = simplexml_load_file($filepath) or die("Error: Cannot create object!");

                $uploadedTable = '<div class="table-container">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>OFFERING CODE</th>
                                                <th>COURSE CODE</th>
                                                <th>SECTION</th>
                                                <th>CLASS DAYS</th>
                                                <th>CLASS START TIME</th>
                                                <th>CLASS END TIME</th>
                                                <th>ENROLL CAP</th>
                                                <th>PROFESSOR</th>
                                                <th>ROOM</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

                foreach ($xml->offering as $offering) {
                    $offering_code = $conn->real_escape_string($offering['offering_code']);
                    $course_code = $conn->real_escape_string($offering->course_code);
                    $section = $conn->real_escape_string($offering->section);
                    $class_days = $conn->real_escape_string($offering->class_days);
                    $class_start_time = $conn->real_escape_string($offering->class_start_time);
                    $class_end_time = $conn->real_escape_string($offering->class_end_time);
                    $enroll_cap = (int)$offering->enroll_cap;
                    $professor = $conn->real_escape_string($offering->professor);
                    $room = $conn->real_escape_string($offering->room);

                    $uploadedTable .= "<tr>
                                        <td>" . htmlspecialchars($offering_code) . "</td>
                                        <td>" . htmlspecialchars($course_code) . "</td>
                                        <td>" . htmlspecialchars($section) . "</td>
                                        <td>" . htmlspecialchars($class_days) . "</td>
                                        <td>" . htmlspecialchars($class_start_time) . "</td>
                                        <td>" . htmlspecialchars($class_end_time) . "</td>
                                        <td>" . htmlspecialchars($enroll_cap) . "</td>
                                        <td>" . htmlspecialchars($professor) . "</td>
                                        <td>" . htmlspecialchars($room) . "</td>
                                      </tr>";

                    // Check for duplicate offering_code
                    $checkDuplicateQuery = "SELECT offering_code FROM section_offerings WHERE offering_code = '$offering_code'";
                    $duplicateResult = $conn->query($checkDuplicateQuery);

                    if ($duplicateResult && $duplicateResult->num_rows > 0) {
                        $error_messages[] = "Duplicate entry: Offering code '$offering_code' already exists.";
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
                $uploadedTable .= '</tbody></table></div>';
            } else {
                $error_messages[] = "Invalid file type. Only XML files are allowed.";
            }
        } else {
            $error_messages[] = "Error uploading file. Please try again.";
        }
    }

    // Handle Delete Offering
    if (isset($_POST["delete_offering"])) {
        $offering_code = $conn->real_escape_string($_POST["offering_code"]);

        // Check if the offering exists in `students_classes`
        $checkDependencies = "SELECT * FROM students_classes WHERE offering_code = '$offering_code'";
        $dependencyResult = $conn->query($checkDependencies);

        if ($dependencyResult && $dependencyResult->num_rows > 0) {
            // Delete dependencies from `students_classes`
            $deleteDependenciesQuery = "DELETE FROM students_classes WHERE offering_code = '$offering_code'";
            if ($conn->query($deleteDependenciesQuery)) {
                $success_messages[] = "Dependencies for offering '$offering_code' deleted successfully from 'students_classes'.";
            } else {
                $error_messages[] = "Error deleting dependencies for offering '$offering_code': " . $conn->error;
            }
        }

        // Attempt to delete the offering itself
        $deleteOfferingQuery = "DELETE FROM section_offerings WHERE offering_code = '$offering_code'";
        if ($conn->query($deleteOfferingQuery)) {
            $success_messages[] = "Offering '$offering_code' deleted successfully!";
        } else {
            $error_messages[] = "Error deleting offering '$offering_code': " . $conn->error;
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
</head>
<body>
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

    <!-- Uploaded XML Table -->
    <?php echo $uploadedTable; ?>

    <!-- Back Button -->
    <div class="back-button">
        <button onclick="window.location.href='admin_create_offerings.php'" class="main-button admin-button">Back</button>
    </div>
</body>
</html>












