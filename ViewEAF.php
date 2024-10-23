<html>
    <head><title>View EAF</title>

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

    $sql = "SELECT s.student_id, s.student_name, c.course_title, so.section, so.class_days, so.class_start_time, so.class_end_time, so.professor, so.room
            FROM students s
            JOIN students_classes sc ON s.student_id = sc.student_id
            JOIN section_offerings so ON sc.offering_code = so.offering_code
            JOIN courses c ON so.course_code = c.course_code
            WHERE s.student_id = '$student_id'";

    $result = $conn->query($sql);
    ?>

</head>
    <body>
        <link rel="stylesheet" href="assets/css/style.css">
        
        <h1 class="itmosys-header">Welcome to ITmosys</h1>

        <div class="container">
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
                </tr>
                <?php
                            }
                        }
                ?>
            </table>
        </div>
    </body>
</html>