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
$uploadedTable = ""; // Store the uploaded XML table

// Handle XML Upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["xml"])) {
    if ($_FILES["xml"]["error"] === UPLOAD_ERR_OK) {
        $filepath = $_FILES["xml"]["tmp_name"];

        $xml = @simplexml_load_file($filepath);

        if ($xml === false) {
            $error_messages[] = "Invalid file type. Only XML files are allowed.";
        } else {
            $uploadedTable .= '<div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>COURSE CODE</th>
                                            <th>COURSE TITLE</th>
                                            <th>UNITS</th>
                                            <th>COREQUISITE</th>
                                            <th>PREREQUISITES</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            foreach ($xml->course as $course) {
                $course_code = htmlspecialchars($course['course_code']);
                $course_title = htmlspecialchars($course->course_title);
                $units = (int)$course->units;
                $co_requisite = isset($course->co_requisite) ? htmlspecialchars($course->co_requisite) : "None";

                $prerequisites = [];
                foreach ($course->prerequisite as $prereq) {
                    $prerequisites[] = htmlspecialchars($prereq);
                }

                $uploadedTable .= "<tr>
                                    <td>$course_code</td>
                                    <td>$course_title</td>
                                    <td>$units</td>
                                    <td>$co_requisite</td>
                                    <td>" . implode(", ", $prerequisites) . "</td>
                                  </tr>";

                try {
                    $conn->query("INSERT IGNORE INTO course_codes (course_code) VALUES ('$course_code')");
                    $conn->query("INSERT INTO courses (course_code, course_title, units, co_requisite) 
                                  VALUES ('$course_code', '$course_title', $units, " . ($co_requisite ? "'$co_requisite'" : "NULL") . ")");
                    foreach ($prerequisites as $prerequisite) {
                        $conn->query("INSERT INTO prerequisites (course_code, prerequisite) VALUES ('$course_code', '$prerequisite')");
                    }
                    $success_messages[] = "Course '$course_code' added successfully!";
                } catch (Exception $e) {
                    $error_messages[] = "Error processing course '$course_code': " . $e->getMessage();
                }
            }

            $uploadedTable .= '</tbody></table></div>';
        }
    } else {
        $error_messages[] = "Error uploading file.";
    }
}

// Handle Delete Course
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_course"])) {
    $course_code = $conn->real_escape_string($_POST["delete_course"]);

    try {
        // Check if the course has current offerings
        $checkOfferings = "SELECT * FROM section_offerings WHERE course_code = '$course_code'";
        $offeringsResult = $conn->query($checkOfferings);

        if ($offeringsResult->num_rows > 0) {
            $error_messages[] = "Course '$course_code' cannot be deleted because it has current offerings.";
        } else {
            // Check if the course is a prerequisite for another course
            $checkPrerequisites = "SELECT * FROM prerequisites WHERE prerequisite = '$course_code'";
            $prereqResult = $conn->query($checkPrerequisites);

            if ($prereqResult->num_rows > 0) {
                $error_messages[] = "Course '$course_code' cannot be deleted because it is a prerequisite for other courses.";
            } else {
                // Delete the course
                $conn->query("DELETE FROM prerequisites WHERE course_code = '$course_code'");
                $conn->query("DELETE FROM courses WHERE course_code = '$course_code'");
                $conn->query("DELETE FROM course_codes WHERE course_code = '$course_code'");
                $success_messages[] = "Course '$course_code' deleted successfully!";
            }
        }
    } catch (Exception $e) {
        $error_messages[] = "Error deleting course '$course_code': " . $e->getMessage();
    }
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

<?php include 'admin_SidebarTopPanel.php'; ?>

<!-- Main Content -->
<div class="content">
    <div class="AdminContainer">
        <h2 class="title-header">Manage Courses</h2>
        <div class="separator"></div>
</head>
<body>
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

    <?php echo $uploadedTable; ?>

    <div class="back-button">
        <button onclick="window.location.href='admin_create_courses.php'" class="main-button admin-button">Back</button>
    </div>

</body>
</html>

