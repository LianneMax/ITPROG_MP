<html>
<head>
    <title>Add Classes</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <?php
        include "../includes/dbconfig.php";
        session_start(); // Start the session

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    ?>
</head>
<body>
    <!-- ITmosys Header -->
    <h1 class="itmosys-header">ITmosys</h1>

    <div class="addClass_container">
        <!-- Title header in the top-left of the box -->
        <h2 class="title-header">Add Classes</h2>

        <!-- Line below the Title header -->
        <div class="separator"></div>

        <?php
            // Initialize feedback message variables
            $alreadyEnrolled = false;
            $invalidCode = false;
            $classAdded = false;
            $classAddError = "";

            // Check if the user is logged in
            if (!isset($_SESSION['student_id'])) {
                die("Error: Please log in to add a class.");
            }

            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get the input offering code
                $offeringCode = mysqli_real_escape_string($conn, $_POST['offeringCode']);
                $studentID = $_SESSION['student_id']; // Use the logged-in student's ID from session

                // Validate if the offering code exists in section_offerings table
                $offeringQuery = "SELECT * FROM section_offerings WHERE offering_code = '$offeringCode'";
                $result = mysqli_query($conn, $offeringQuery);

                if (mysqli_num_rows($result) > 0) {
                    // Check if the student is already enrolled in the course
                    $checkEnrollmentQuery = "SELECT * FROM students_classes WHERE student_id = '$studentID' AND offering_code = '$offeringCode'";
                    $enrollmentResult = mysqli_query($conn, $checkEnrollmentQuery);

                    if (mysqli_num_rows($enrollmentResult) == 0) {
                        // Insert the class for the student
                        $insertQuery = "INSERT INTO students_classes (student_id, offering_code) VALUES ('$studentID', '$offeringCode')";
                        if (mysqli_query($conn, $insertQuery)) {
                            $classAdded = true; // Set success message
                        } else {
                            $classAddError = mysqli_error($conn); // Set error message
                        }
                    } else {
                        $alreadyEnrolled = true; // Set already enrolled message
                    }
                } else {
                    $invalidCode = true; // Set invalid code message
                }
            }
        ?>

        <div>
            <!-- Form to add class -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="offeringCode">Class code to add:</label>
                <input type="text" name="offeringCode" placeholder="ex: 1234" required> <br /><br />

                <!-- Display feedback messages here -->
                <?php
                    // Show feedback message if form is submitted
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Display error if already enrolled
                        if ($alreadyEnrolled) {
                            echo "<p style='color:red;'>You are already enrolled in this class.</p>";
                        }
                        // Display error if the course code is invalid
                        elseif ($invalidCode) {
                            echo "<p style='color:red;'>Invalid offering code. Please try again.</p>";
                        }
                        // Display success message if class added
                        elseif ($classAdded) {
                            echo "<p style='color:green;'>Class added successfully!</p>";
                        }
                        // Display general error message if there's an issue
                        elseif ($classAddError) {
                            echo "<p style='color:red;'>Error adding class: " . $classAddError . "</p>";
                        }
                    }
                ?>

                <!-- Submit button -->
                <button type="submit">Add Class</button>
            </form>
        </div>
        
        <h2 class="subtitle-header">Your Current Classes</h2>
        <table>
            <tr>
                <th>Code</th>
                <th>Class</th>
                <th>Section</th>
                <th>Class Days</th>
                <th>Class Start Time</th>
                <th>Class End Time</th>
                <th>Enroll Cap</th>
                <th>Enrolled</th>
                <th>Professor</th>
                <th>Room</th>
            </tr>

            <?php
            // Query to get the currently enrolled classes for the logged-in student
            $studentID = $_SESSION['student_id']; // Get student ID from session
            $enrolledClassesQuery = "
                SELECT so.offering_code, so.course_code, so.section, so.class_days, so.class_start_time, so.class_end_time, 
                       so.enroll_cap, so.enrolled_students, so.professor, so.room
                FROM students_classes sc
                INNER JOIN section_offerings so ON sc.offering_code = so.offering_code
                WHERE sc.student_id = '$studentID'
            ";
            $enrolledResult = mysqli_query($conn, $enrolledClassesQuery);

            // Loop through the results and display them in the table
            if (mysqli_num_rows($enrolledResult) > 0) {
                while ($row = mysqli_fetch_assoc($enrolledResult)) {
                    echo "<tr>";
                    echo "<td><b style='color:green;'>" . $row['offering_code'] . "</b></td>";
                    echo "<td><b style='color:green;'>" . $row['course_code'] . "</b></td>";
                    echo "<td><b style='color:green;'>" . $row['section'] . "</b></td>";
                    echo "<td>" . $row['class_days'] . "</td>";
                    echo "<td>" . $row['class_start_time'] . "</td>";
                    echo "<td>" . $row['class_end_time'] . "</td>";
                    echo "<td>" . $row['enroll_cap'] . "</td>";
                    echo "<td>" . $row['enrolled_students'] . "</td>";
                    echo "<td>" . $row['professor'] . "</td>";
                    echo "<td>" . $row['room'] . "</td>";
                    echo "</tr>";
                }
            } else {
                //Failure Message
                echo "<p style='color:red;'>No Class Found</p>";
            }

                // Close connection
                $conn->close();
            ?>
        </table>

    </div>
</body>
</html>
