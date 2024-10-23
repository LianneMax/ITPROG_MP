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

 
        <div class="top-panel">
                <h1 class="itmosys-header">ITMOSYS</h1>
        </div>


    <div class="addClass_container">
        <!-- Title header in the top-left of the box -->
        <h2 class="title-header">ADD CLASSES</h2>

        <!-- Line below the Title header -->
        <div class="separator"></div>

        <?php
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
                            echo "<p style='color:green;'>Class added successfully!</p>";
                        } else {
                            echo "<p style='color:red;'>Error adding class: " . mysqli_error($conn) . "</p>";
                        }
                    } else {
                        echo "<p style='color:red;'>You are already enrolled in this class.</p>";
                    }
                } else {
                    echo "<p style='color:red;'>Invalid offering code. Please try again.</p>";
                }
            }
        ?>

        <div>
            <!-- Form to add class -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display: flex; align-items: center; gap: 10px;">
                <label for="offeringCode">Class code to add:</label>
                <input type="text" name="offeringCode" placeholder="ex: 1234" required>
                <button type="submit" class="addclass-btn">Add Class</button>
            </form>
        </div>
        
        <!-- Missing quotation mark fixed here -->
        <div style="width: 100%; text-align: center;">
            <h2 class="sub-header">Your Current Classes</h2>
            <table style="margin: 0 auto; width: 80%;">
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
                    // Failure Message
                    echo "<p>Have Not Enrolled Any Classes</p>";
                }

                // Close connection
                $conn->close();
                ?>
            </table>
        </div>
    </div>
    <button class="logout-btn" onclick="window.location.href='LogoutPage.php'">Logout</button>

</body>
</html>


