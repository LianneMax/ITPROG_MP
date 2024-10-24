<html>
    <head><title>View EAF</title>
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

    if (isset($_SESSION['student_id'])) {
        $student_id = $_SESSION['student_id'];
    }

    $sql = "SELECT s.student_id, s.student_name, c.course_title, c.units, so.section, so.class_days, so.class_start_time, so.class_end_time, so.professor, so.room
    FROM students s
    JOIN students_classes sc ON s.student_id = sc.student_id
    JOIN section_offerings so ON sc.offering_code = so.offering_code
    JOIN courses c ON so.course_code = c.course_code
    WHERE s.student_id = '$student_id'";

    $result = $conn->query($sql);

    // Initialize sum variable
    $sum = null;

    if (isset($_POST['getSum'])) {
        // SQL query to get the sum of the column
        $sql = "SELECT SUM(units) AS total_sum FROM numbers_table";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Fetch result
            $row = $result->fetch_assoc();
            $sum = $row['total_sum'];
        } else {
            $sum = 0;
        }
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
        <h1 class="itmosys-header">ITmosys</h1>
    </div>    <!-- ITmosys Header -->

    <div class="content">
        <div class="viewEAF_container">
            <h2 class="header2">View Student EAF</h2>
            <div class="separator"></div>
            <?php
                echo "<h3>Welcome!</h3>";
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
            </table>


        </div>
    </div>
    
        <script src="../includes/main.js"></script>
    </body>
</html>