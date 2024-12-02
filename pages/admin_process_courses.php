<!-- 
        
    This page inserts the Course XML File Contents if valid 

    Last updated: November 28, 2024, by Lianne Balbastro

    TODO: 
        **Polish SQL Error handling**
        
        PENDING: Manual Input Option
        DONE: Fix User interface (Pls put UI elements in one include file, to avoid copy pasting sidebars, buttons, etc)
        DONE: Sidebar must contain admin-specific options
 -->
 <html>
<head>
    <title>Process Courses</title>
    <link rel="stylesheet" href="../assets/css/admin.css"> <!-- Link to the CSS file -->
</head>
<body>

    <?php
    include "../includes/dbconfig.php";
    session_start();

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Initialize session-based messages
    if (!isset($_SESSION['error_messages'])) {
        $_SESSION['error_messages'] = [];
    }
    if (!isset($_SESSION['success_messages'])) {
        $_SESSION['success_messages'] = [];
    }

    // Handle POST requests
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Delete course functionality
        if (isset($_POST['delete_course'])) {
            $course_code = htmlspecialchars($_POST['delete_course']);
            try {
                // Check if the course is a prerequisite for another course
                $checkPrereq = "SELECT * FROM prerequisites WHERE prerequisite = '$course_code'";
                $prereqResult = $conn->query($checkPrereq);

                if ($prereqResult->num_rows > 0) {
                    $_SESSION['error_messages'][] = "Course '$course_code' cannot be deleted because it is a prerequisite for other courses.";
                } else {
                    // Check if the course has section offerings
                    $checkOfferings = "SELECT * FROM section_offerings WHERE course_code = '$course_code'";
                    $offeringsResult = $conn->query($checkOfferings);

                    if ($offeringsResult->num_rows > 0) {
                        $_SESSION['error_messages'][] = "Course '$course_code' cannot be deleted because it has existing section offerings.";
                    } else {
                        // Delete the course
                        $conn->query("DELETE FROM prerequisites WHERE course_code = '$course_code'");
                        $conn->query("DELETE FROM courses WHERE course_code = '$course_code'");
                        $conn->query("DELETE FROM course_codes WHERE course_code = '$course_code'");
                        $_SESSION['success_messages'][] = "Course '$course_code' deleted successfully!";
                    }
                }
            } catch (mysqli_sql_exception $e) {
                $_SESSION['error_messages'][] = "Error deleting course '$course_code': " . $e->getMessage();
            }
        }

        // Handle XML file upload
        if (isset($_FILES["xml"])) {
            if ($_FILES["xml"]["error"] === UPLOAD_ERR_OK) {
                $mime_types = ["text/xml", "application/xml"];
                if (in_array($_FILES["xml"]["type"], $mime_types)) {
                    $filepath = $_FILES["xml"]["tmp_name"];
                    $xml = simplexml_load_file($filepath) or die("Error: Cannot create object!");

                    // Display uploaded XML contents in a table
                    echo '<div class="table-container">
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
                        $course_code = $conn->real_escape_string($course['course_code']);
                        $course_title = $conn->real_escape_string($course->course_title);
                        $units = (int)$course->units;
                        $co_requisite = isset($course->co_requisite) ? $conn->real_escape_string($course->co_requisite) : null;

                        $prerequisites = [];
                        foreach ($course->prerequisite as $prereq) {
                            $prerequisites[] = $conn->real_escape_string($prereq);
                        }

                        // Display the row
                        echo "<tr>
                                <td>" . htmlspecialchars($course_code) . "</td>
                                <td>" . htmlspecialchars($course_title) . "</td>
                                <td>" . htmlspecialchars($units) . "</td>
                                <td>" . htmlspecialchars($co_requisite ?? '') . "</td>
                                <td>" . htmlspecialchars(implode(", ", $prerequisites)) . "</td>
                              </tr>";

                        // Insert data into the database
                        try {
                            $conn->query("INSERT IGNORE INTO course_codes (course_code) VALUES ('$course_code')");
                            $conn->query("INSERT INTO courses (course_code, course_title, units, co_requisite) 
                                          VALUES ('$course_code', '$course_title', $units, " . ($co_requisite ? "'$co_requisite'" : "NULL") . ")");

                            foreach ($prerequisites as $prerequisite) {
                                $conn->query("INSERT INTO prerequisites (course_code, prerequisite) 
                                              VALUES ('$course_code', '$prerequisite')");
                            }
                            $_SESSION['success_messages'][] = "Course '$course_code' added successfully!";
                        } catch (mysqli_sql_exception $e) {
                            if ($e->getCode() === 1062) {
                                $_SESSION['error_messages'][] = "Duplicate entry for course code '$course_code'.";
                            } else {
                                $_SESSION['error_messages'][] = "Error adding course '$course_code': " . $e->getMessage();
                            }
                        }
                    }

                    echo '</tbody></table></div>';
                } else {
                    $_SESSION['error_messages'][] = "Invalid file type. Only XML files are allowed.";
                }
            } else {
                $_SESSION['error_messages'][] = "Error uploading file. Please try again.";
            }
        }
    }

    // Display error messages
    if (!empty($_SESSION['error_messages'])) {
        echo '<div class="error-messages">';
        foreach ($_SESSION['error_messages'] as $error) {
            echo "<p class='error-message'>$error</p>";
        }
        echo '</div>';
        $_SESSION['error_messages'] = [];
    }

    // Display success messages
    if (!empty($_SESSION['success_messages'])) {
        echo '<div class="success-messages">';
        foreach ($_SESSION['success_messages'] as $success) {
            echo "<p class='success-message'>$success</p>";
        }
        echo '</div>';
        $_SESSION['success_messages'] = [];
    }

    $conn->close();
    ?>

    <!-- Back Button -->
    <div class="back-button">
        <button onclick="window.location.href='admin_create_courses.php'" class="main-button admin-button">Back</button>
    </div>
</body>
</html>












