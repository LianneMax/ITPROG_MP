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

        //getting the current enrolled subjects of the student
        $sql = "SELECT s.student_id, s.student_name, c.course_title, c.units, so.section, so.class_days, so.class_start_time, so.class_end_time, so.professor, so.room
        FROM students s
        JOIN students_classes sc ON s.student_id = sc.student_id
        JOIN section_offerings so ON sc.offering_code = so.offering_code
        JOIN courses c ON so.course_code = c.course_code
        WHERE s.student_id = '$student_id'";

        $result = $conn->query($sql);

        // Initialize sum variable
        $sum = null;

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
        ?>
    </head>
    <body>
        
<!-- Hamburger Menu -->
<div id="hamburger" class="hamburger">
    <i class="fas fa-bars"></i>
</div>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <div class="separator" style="margin-top: 50px"></div>
    <a href="add_class.php">
        <i class="fas fa-plus-circle"></i>
        <span class="link-text">Add Class</span>
    </a>
    <a href="drop_class.php">
        <i class="fas fa-minus-circle"></i>
        <span class="link-text">Drop Class</span>
    </a>
    <a href="CourseOfferings.php">
        <i class="fas fa-shopping-basket"></i>
        <span class="link-text">Course Offerings</span>
    </a>
    <a href="ViewEAF.php">
        <i class="fas fa-calendar-alt"></i>
        <span class="link-text">View EAF</span>
    </a>
    <div class="separator" style="margin-top: 10px"></div>
    <!-- Logout Button -->
    <button class="logout-btn" onclick="window.location.href='LogoutPage.php'">
        <i class="fas fa-sign-out-alt"></i>
        <span class="link-text">Logout</span>
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
                <tr>
                    <?php
                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
                    ?>
                            <td><?php echo $row["course_title"];?></td>
                            <td><?php echo $row["section"];?></td>
                            <td><?php echo $row["class_days"];?></td>
                            <td><?php echo $row["class_start_time"];?></td>
                            <td><?php echo $row["class_end_time"];?></td>
                            <td><?php echo $row["professor"];?></td>
                            <td><?php echo $row["room"];?></td>
                            <td><?php echo $row["units"];?></td>
                </tr>
                <?php
                            }
                        }
                ?>
                <tr>
                    <td colspan="7">Total Units:</td>
                    <td><?php echo "$sum";?></td>
                </tr>
            </table>

            <!-- Enrollment Confirmation button -->
            <div class="separator"></div>

            <?php
                if (isset($_POST['confirmEnrollment'])) {
                    if ($sum <= 18) {
                        echo "You have confirmed your enrollment.";

                        // SQL query to insert records from student_classes to past_classes
                        $copyQuery = "INSERT INTO past_classes (student_id, class_id, enrollment_date)
                        SELECT student_id, class_id, enrollment_date
                        FROM student_classes
                        WHERE student_id = ?";

                        // Prepare statement
                        $stmt = $conn->prepare($copyQuery);
                        $stmt->bind_param("i", $student_id);  // Binding the student ID to the query

                        if ($stmt->execute()) {
                        echo "Subjects successfully moved to past_classes.";
                        } else {
                        echo "Error moving subjects: " . $stmt->error;
                        }

                        $stmt->close();
                    } else {
                        echo "Your total units exceed the limit of 18 units. Please adjust your enrolled courses.";
                    }
                }
            ?>

            <form method="post">
                <button type="submit" name="confirmEnrollment" class="login_button">
                    Confirm your Enrollment here:
                </button>
            </form>
        </div>
    </div>
        <script src="../includes/main.js"></script>
    </body>
</html>