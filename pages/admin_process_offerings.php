<!-- 
    admin_process_offerings.php
    This page inserts the Course Offering XML File Contents if valid 

    Last updated: November 29, 2024 | 3:27PM by Jeremiah Ang

    TODO: 
        
 -->

<html>
<head>
    <title>Process Course Offerings</title>
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
                    <th>OFFERING CODE</th>
                    <th>COURSE CODE</th>
                    <th>SECTION</th>
                    <th>CLASS DAYS</th>
                    <th>CLASS START TIME</th>
                    <th>CLASS END TIME</th>
                    <th>ENROLL CAP</th>
                    <th>ENROLLED STUDENTS</th>
                    <th>PROFESSOR</th>
                    <th>ROOM</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($xml->offering as $offering) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($offering['offering_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($offering->course_code) . "</td>";
                    echo "<td>" . htmlspecialchars($offering->section) . "</td>";
                    echo "<td>" . htmlspecialchars($offering->class_days) . "</td>";
                    echo "<td>" . htmlspecialchars($offering->class_start_time) . "</td>";
                    echo "<td>" . htmlspecialchars($offering->class_end_time) . "</td>";
                    echo "<td>" . htmlspecialchars($offering->enroll_cap) . "</td>";
                    echo "<td>" . 0 . "</td>"; //enrolled_students == 0 by default
                    echo "<td>" . htmlspecialchars($offering->professor) . "</td>";
                    echo "<td>" . htmlspecialchars($offering->room) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    // Loop through each offering and insert into the database
    foreach ($xml->offering as $offering) {
        $offering_code = $conn->real_escape_string($offering['offering_code']);
        $course_code = $conn->real_escape_string($offering->course_code);
        $section = $conn->real_escape_string($offering->section);
        $class_days = $conn->real_escape_string($offering->class_days);
        $class_start_time = $conn->real_escape_string($offering->class_start_time);
        $class_end_time = $conn->real_escape_string($offering->class_end_time);

        $enroll_cap = (int)$offering->enroll_cap;
        $enrolled_students = 0;
        $professor = $conn->real_escape_string($offering->professor);
        $room = $conn->real_escape_string($offering->room);

        // Insert offering details into `section_offerings` table
        $insertQuery = "INSERT INTO section_offerings (offering_code, course_code, section, class_days, class_start_time, class_end_time, 
                                                       enroll_cap, enrolled_students, professor, room) 
                        VALUES ('$offering_code', '$course_code', '$section', '$class_days', '$class_start_time', '$class_end_time',
                                $enroll_cap, $enrolled_students, '$professor', '$room')";
                                //no quotations on INTEGERS enroll_cap & enrolled_students
        if (!mysqli_query($conn, $insertQuery)) {
            echo "<p class='error-message'>Error adding course offering: " . mysqli_error($conn) . "</p>";
            continue;
        }

        echo "<p class='success-message'>Course offering for '$offering_code - '$course_code' added successfully!</p>";
    }

    // Close the connection
    $conn->close();
    ?>
</body>
</html>


