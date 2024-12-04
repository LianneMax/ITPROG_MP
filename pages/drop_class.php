<html>
<head>
    <title>Drop Classes</title>
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
    ?>
</head>
<body>

<!-- Hamburger Menu Button -->
<div id="hamburger" class="hamburger">
    <i class="fas fa-bars"></i>
</div>

<?php include 'SidebarTopPanel.php'; ?>

    <!-- Main Content -->
    <div class="content">
        <div class="addClass_container">
            <h2 class="title-header">DROP CLASSES</h2>
            <div class="separator"></div>

            <?php
                if (!isset($_SESSION['student_id'])) {
                    die("Error: Please log in to add a class.");
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $offeringCode = mysqli_real_escape_string($conn, $_POST['offeringCode']);
                    $studentID = $_SESSION['student_id'];

                    $offeringQuery = "SELECT * FROM section_offerings WHERE offering_code = '$offeringCode'";
                    $result = mysqli_query($conn, $offeringQuery);

                    if (mysqli_num_rows($result) > 0) {
                        $checkEnrollmentQuery = "SELECT * FROM students_classes WHERE student_id = '$studentID' AND offering_code = '$offeringCode'";
                        $enrollmentResult = mysqli_query($conn, $checkEnrollmentQuery);

                        //if there's a record of that class code, DELETE
                        if (mysqli_num_rows($enrollmentResult) > 0) {
                            $deleteQuery = "DELETE FROM students_classes WHERE student_id = '$studentID' AND offering_code = '$offeringCode' ";
                            if (mysqli_query($conn, $deleteQuery)) {

                                //UPDATE enrolled students count, IF enrolled count is 0, do not - 1
                                //Fixed 11/28/2024, 4:57PM
                                $updateEnrollmentQuery = "
                                UPDATE section_offerings 
                                SET enrolled_students = CASE
                                    WHEN enrolled_students > 0 THEN enrolled_students - 1
                                    ELSE enrolled_students
                                END
                                WHERE offering_code = '$offeringCode'
                                ";

                                //If the query is not successful, display error
                                mysqli_query($conn, $updateEnrollmentQuery);
                                    // echo "<p style='color:red;'>Error dropping class: " . mysqli_error($conn) . "</p>";

                                echo "<p style='color:green;'>Class dropped!</p>";
                            } else {
                                echo "<p style='color:red;'>Error dropping class: " . mysqli_error($conn) . "</p>";
                            }
                        } 
                        else {
                            echo "<p style='color:red;'>No classes to drop.</p>";
                        }
                    } else {
                        echo "<p style='color:red;'>Invalid offering code. Please try again.</p>";
                    }
                }
            ?>

            <div>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display: flex; align-items: center; gap: 10px;">
                    <label for="offeringCode">Class code to DROP:</label>
                    <input type="text" name="offeringCode" placeholder="ex: 1234" required>
                    <button type="submit" class="main-button addclass-btn">Drop Class</button>
                </form>
            </div>

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
                    $studentID = $_SESSION['student_id'];
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
                        echo "<p>Have Not Enrolled Any Classes</p>";
                    }

                    $conn->close();
                    ?>
                </table>
            </div>
        </div>
    </div>

    <script src="../includes/main.js"></script>
</body>
</html>


