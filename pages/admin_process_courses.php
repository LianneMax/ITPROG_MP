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

    // Check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        exit("POST request method required");
    }

    // Check if files are uploaded
    if (empty($_FILES)) {
        exit("$_FILES is empty. Enable file_uploads (file_uploads=On) in C:\\xampp\\php\\php.ini");
    }

    // Check for file upload errors
    if ($_FILES["xml"]["error"] !== UPLOAD_ERR_OK) {
        switch ($_FILES["xml"]["error"]) {
            case UPLOAD_ERR_PARTIAL:
                exit("File only partially uploaded");
            case UPLOAD_ERR_NO_FILE:
                exit("No file was uploaded");
            case UPLOAD_ERR_CANT_WRITE:
                exit("Failed to write file");
            case UPLOAD_ERR_NO_TMP_DIR:
                exit("Temp folder not found");
            default:
                exit("Unknown file error");
        }
    }

    // Restrict file type to XML only
    $mime_types = ["text/xml", "application/xml"];
    if (!in_array($_FILES["xml"]["type"], $mime_types)) {
        exit("Invalid file type");
    }

    // Copy the file path
    $filepath = $_FILES["xml"]["tmp_name"];

    // Load the XML file
    $xml = simplexml_load_file($filepath) or die("Error: cannot create object!");

    ?>
    <!-- Table of XML Contents -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>COURSE CODE</th>
                    <th>COURSE TITLE</th>
                    <th>UNITS</th>
                    <th>COREQUISITE</th>
                    <th>PREREQUISITE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($xml->course as $course) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($course['course_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($course->course_title) . "</td>";
                    echo "<td>" . htmlspecialchars($course->units) . "</td>";
                    echo "<td>" . (isset($course->co_requisite) ? htmlspecialchars($course->co_requisite) : "") . "</td>";

                    // Handle prerequisites
                    $prerequisites = [];
                    foreach ($course->prerequisite as $prereq) {
                        $prerequisites[] = htmlspecialchars($prereq);
                    }
                    echo "<td>" . implode(", ", $prerequisites) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    // Loop through each course and insert into the database
    foreach ($xml->course as $course) {
        $course_code = $conn->real_escape_string($course['course_code']);
        $course_title = $conn->real_escape_string($course->course_title);
        $units = (int)$course->units;
        $co_requisite = isset($course->co_requisite) ? $conn->real_escape_string($course->co_requisite) : null;

        // Insert course code into `course_codes` table
        $insertQuery = "INSERT IGNORE INTO course_codes (course_code) VALUES ('$course_code')";
        if (!mysqli_query($conn, $insertQuery)) {
            echo "<p class='error-message'>Error adding course code: " . mysqli_error($conn) . "</p>";
        }

        // Insert course details into `courses` table
        $insertQuery = "INSERT INTO courses (course_code, course_title, units, co_requisite) 
                        VALUES ('$course_code', '$course_title', $units, " . ($co_requisite ? "'$co_requisite'" : "NULL") . ")";
        if (!mysqli_query($conn, $insertQuery)) {
            echo "<p class='error-message'>Error adding course: " . mysqli_error($conn) . "</p>";
            continue;
        }

        // Insert prerequisites into `prerequisites` table
        foreach ($course->prerequisite as $prereq) {
            $prerequisite = $conn->real_escape_string($prereq);
            $insertQuery = "INSERT INTO prerequisites (course_code, prerequisite) 
                            VALUES ('$course_code', '$prerequisite')";
            if (!mysqli_query($conn, $insertQuery)) {
                echo "<p class='error-message'>Error adding prerequisite: " . mysqli_error($conn) . "</p>";
            }
        }

        echo "<p class='success-message'>Course and prerequisites for '$course_code' added successfully!</p>";
    }

    // Close the connection
    $conn->close();
    ?>
</body>
</html>


