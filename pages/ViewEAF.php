<html>
    <head>
        <title>View EAF</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/navigation.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <?php
        include "../includes/dbconfig.php";
        session_start(); // Start the session

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //assigns the student ID number of the current session
        if (isset($_SESSION['student_id'])) {
            $student_id = $_SESSION['student_id'];
        }

        // Initialize a variable to track enrollment confirmation
        $enrollmentConfirmed = false;

        //get the current enrolled class schedule of the student
        $sql = "SELECT s.student_id, s.student_lastname, s.student_firstname, c.course_title, c.units, so.section, so.class_days,
                so.class_start_time, so.class_end_time, so.professor, so.room
                FROM students AS s
                INNER JOIN students_classes AS sc ON s.student_id = sc.student_id
                INNER JOIN section_offerings AS so ON sc.offering_code = so.offering_code
                INNER JOIN courses AS c ON so.course_code = c.course_code
                WHERE s.student_id = $student_id";

        $result = $conn->query($sql);

        // Initialize sum variable
        $sum = null;

        // Get the sum of all the units
        $sql_sum = "SELECT SUM(c.units) AS total_sum
                    FROM students s
                    JOIN students_classes sc ON s.student_id = sc.student_id
                    JOIN section_offerings so ON sc.offering_code = so.offering_code
                    JOIN courses c ON so.course_code = c.course_code
                    WHERE s.student_id = '$student_id'";

        $result_sum = $conn->query($sql_sum);

        if ($result_sum->num_rows > 0) {
            // Fetch result
            $row = $result_sum->fetch_assoc();
            $sum = $row['total_sum'];
        } else {
            $sum = 0;
        }

        // Only proceed if the total units (sum) are less than or equal to 18
        if ($sum<=18) {
            // SQL query to insert records from student_classes to past_classes
            $copyQuery = "
            INSERT INTO past_enrollments (student_id, course_code, date_enrolled, section, class_days, class_start_time, class_end_time, professor, room, grade)
            SELECT sc.student_id, so.course_code, CURDATE() AS date_enrolled, so.section, so.class_days, so.class_start_time, so.class_end_time, so.professor, so.room, NULL AS grade
            FROM students_classes AS sc
            INNER JOIN section_offerings AS so ON sc.offering_code = so.offering_code
            WHERE sc.student_id = $student_id";

            // Prepare statement
            $stmt = $conn->prepare($copyQuery);

            // Execute the statement
            if ($stmt->execute()) {
                // Set enrollmentConfirmed to true on successful enrollment
                $enrollmentConfirmed = true;
            } else {
                // If execution fails, print error message
                echo "Error moving subjects: " . $stmt->error;
            }

            $stmt->close();
        }
        ?>
    </head>
    <body>
        
<!-- Hamburger Menu Button -->
<div id="hamburger" class="hamburger">
    <i class="fas fa-bars"></i>
</div>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
        <div class="separator" style="margin-top: 50px;"></div>
    <!-- Sidebar Buttons -->
    <button class="sidebar-btn" onclick="window.location.href='add_class.php'">
        <i class="fas fa-plus-circle"></i>
        <span class="link-text">Add Class</span>
    </button>
    <button class="sidebar-btn" onclick="window.location.href='drop_class.php'">
        <i class="fas fa-minus-circle"></i>
        <span class="link-text">Drop Class</span>
    </button>
    <button class="sidebar-btn" onclick="window.location.href='CourseOfferings.php'">
        <i class="fas fa-shopping-basket"></i>
        <span class="link-text">Course Offerings</span>
    </button>
    <button class="sidebar-btn" onclick="window.location.href='ViewEAF.php'">
        <i class="fas fa-calendar-alt"></i>
        <span class="link-text">View EAF</span>
    </button>
    
    <div class="separator" style="margin-top: 10px;"></div>

    <!-- Logout Button -->
    <button class="logout-btn" onclick="window.location.href='LogoutPage.php'">
        <i class="fas fa-sign-out-alt"></i>
    </button>
</div>

<!-- Top Panel -->
<div class="top-panel">
    <h1 class="itmosys-header">ITmosys</h1>
</div>

        <div class="content">
            <div class="viewEAF_container">
                <h2 class="header2">View Student EAF</h2>
                <div class="separator"></div>
                <?php
                    echo "<h2>Welcome!</h2>";
                    echo "<h3>Student ID: $student_id</h3>";
                ?>

                <table>
                    <tr>
                        <th>Course Title</th>
                        <th>Section</th>
                        <th>Class Days</th>
                        <th>Class Start Time</th>
                        <th>Class End Time</th>
                        <th>Professor</th>
                        <th>Room</th>
                        <th>Units</th>
                    </tr>
                    <?php
                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?php echo $row["course_title"]; ?></td>
                            <td><?php echo $row["section"]; ?></td>
                            <td><?php echo $row["class_days"]; ?></td>
                            <td><?php echo $row["class_start_time"]; ?></td>
                            <td><?php echo $row["class_end_time"]; ?></td>
                            <td><?php echo $row["professor"]; ?></td>
                            <td><?php echo $row["room"]; ?></td>
                            <td><?php echo $row["units"]; ?></td>
                        </tr>
                    <?php
                            }
                        }
                    ?>
                    <tr>
                        <td colspan="7">Total Units:</td>
                        <td><?php echo "$sum"; ?></td>
                    </tr>
                </table>

                <div class="separator"></div>

                <?php
                    if (isset($_POST['confirmEnrollment'])) {
                        if ($sum <= 18) {
                            echo "<p>You have confirmed your enrollment.</p>";
                        } else {
                            echo "<p>Your total units exceed the limit of 18 units. Please adjust your enrolled courses.</p>";
                        }
                    }
                ?>

                <?php if (!$enrollmentConfirmed): ?>
                    <!-- Show the button only if enrollment is not confirmed -->
                    <form method="post">
                        <button type="submit" name="confirmEnrollment" class="login_button">
                            Confirm your Enrollment here:
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <script src="../includes/main.js"></script>
    </body>
</html>