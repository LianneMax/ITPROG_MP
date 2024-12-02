<!-- 
    admin_process_offerings.php
    This page inserts the Course Offering XML File Contents if valid 

    Last updated: November 29, 2024 | 3:27PM by Jeremiah Ang

    TODO: 
        **Polish SQL Error handling**
    
        PENDING: Manual Input Option
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

    $error_messages = [];
    $success_messages = [];
    $uploadedTable = ""; // Store the uploaded table HTML

    // Handle POST requests for adding/deleting offerings or uploading XML
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

                        $insertQuery = "INSERT INTO section_offerings (offering_code, course_code, section, class_days, class_start_time, class_end_time, enroll_cap, professor, room) 
                                        VALUES ('$offering_code', '$course_code', '$section', '$class_days', '$class_start_time', '$class_end_time', $enroll_cap, '$professor', '$room')";
                        if ($conn->query($insertQuery)) {
                            $success_messages[] = "Offering '$offering_code' added successfully!";
                        } else {
                            $error_messages[] = "Error adding offering '$offering_code': " . $conn->error;
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
            $offering_code = $conn->real_escape_string($_POST["delete_offering"]);

            $deleteQuery = "DELETE FROM section_offerings WHERE offering_code = '$offering_code'";
            if ($conn->query($deleteQuery)) {
                $success_messages[] = "Offering '$offering_code' deleted successfully!";
            } else {
                $error_messages[] = "Error deleting offering '$offering_code': " . $conn->error;
            }
        }
    }

    // Fetch dropdown options for professors and courses
    $professorDropdownOptions = "";
    $professorQuery = $conn->query("SELECT prof_fullname FROM professors");
    while ($row = $professorQuery->fetch_assoc()) {
        $professorDropdownOptions .= "<option value='" . htmlspecialchars($row['prof_fullname']) . "'>" . htmlspecialchars($row['prof_fullname']) . "</option>";
    }

    $courseDropdownOptions = "";
    $courseQuery = $conn->query("SELECT course_code FROM course_codes");
    while ($row = $courseQuery->fetch_assoc()) {
        $courseDropdownOptions .= "<option value='" . htmlspecialchars($row['course_code']) . "'>" . htmlspecialchars($row['course_code']) . "</option>";
    }
    ?>

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

    <!-- Manual Add Offering -->
    <div class="form-container">
        <form method="POST" action="">
            <h4>Add Offering</h4>
            <label for="offering_code">Offering Code:</label>
            <input type="text" id="offering_code" name="offering_code" pattern="\d+" title="Offering code must be numeric" required>

            <label for="course_code">Course Code:</label>
            <select id="course_code" name="course_code" required>
                <?php echo $courseDropdownOptions; ?>
            </select>

            <label for="section">Section:</label>
            <input type="text" id="section" name="section" required>

            <label for="class_days">Class Days:</label>
            <div class="checkbox-container">
                <input type="checkbox" name="class_days[]" value="M"> M
                <input type="checkbox" name="class_days[]" value="T"> T
                <input type="checkbox" name="class_days[]" value="W"> W
                <input type="checkbox" name="class_days[]" value="TH"> TH
                <input type="checkbox" name="class_days[]" value="F"> F
                <input type="checkbox" name="class_days[]" value="S"> S
            </div>

            <label for="class_start_time">Class Start Time:</label>
            <input type="time" id="class_start_time" name="class_start_time" required>

            <label for="class_end_time">Class End Time:</label>
            <input type="time" id="class_end_time" name="class_end_time" required>

            <label for="enroll_cap">Enrollment Cap:</label>
            <input type="number" id="enroll_cap" name="enroll_cap" required>

            <label for="professor">Professor:</label>
            <select id="professor" name="professor" required>
                <?php echo $professorDropdownOptions; ?>
            </select>

            <label for="room">Room:</label>
            <input type="text" id="room" name="room" required>

            <button type="submit" name="add_offering" class="main-button admin-button">Add Offering</button>
        </form>
    </div>
</body>
</html>







