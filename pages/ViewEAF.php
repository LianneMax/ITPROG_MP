<html>
    <head>
        <title>View EAF</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/navigation.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <?php
        session_start(); //starts the session

        //After turning on SQL on XAMPP, you can manage the DB via this url:
        // http://localhost/phpmyadmin
        $server = "localhost";
        $username = "root"; //will change this later
        $password = "";
        $dbname = "itmosys_db";

        //Create connection
        $conn = new mysqli($server, $username, $password, $dbname);

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
        <h2 style="margin-top: 50px">ENROLLMENT</h2>
        <div class="separator"></div>

        <a href="add_class.php"><i class="fas fa-plus-circle"></i> Add Class</a>
        <a href="drop_class.php"><i class="fas fa-minus-circle"></i> Drop Class</a>
        <a href="CourseOfferings.php"><i class="fas fa-shopping-basket"></i> Course Offerings</a>
        <a href="ViewEAF.php"><i class="fas fa-calendar-alt"></i> View EAF</a>
        
        <div class="separator"></div>
        <button class="logout-btn" onclick="window.location.href='LogoutPage.php'">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </div>

    <div class="top-panel">
        <!-- ITmosys Header -->
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