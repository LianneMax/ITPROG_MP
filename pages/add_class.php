<html>
<head>
    <title>Add Classes</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navigation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <?php
        include "../includes/dbconfig.php";
        session_start();

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

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <div class="separator" style="margin-top: 50px;"></div>
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

<!-- Main Content -->
<div class="content">
    <div class="addClass_container">
        <h2 class="title-header">ADD CLASSES</h2>
        <div class="separator"></div>

        <?php
            if (!isset($_SESSION['student_id'])) {
                die("Error: Please log in to add a class.");
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $offeringCode = mysqli_real_escape_string($conn, $_POST['offeringCode']);
                $studentID = $_SESSION['student_id'];

                // Get the course code and enrollment details
                $offeringQuery = "
                    SELECT course_code, enroll_cap, enrolled_students 
                    FROM section_offerings 
                    WHERE offering_code = '$offeringCode'
                ";
                $result = mysqli_query($conn, $offeringQuery);

                if (mysqli_num_rows($result) > 0) {
                    $offering = mysqli_fetch_assoc($result);
                    $courseCode = $offering['course_code'];
                    $enrollCap = $offering['enroll_cap'];
                    $enrolledStudents = $offering['enrolled_students'];

                    // Check if class is full
                    if ($enrolledStudents >= $enrollCap) {
                        echo "<p style='color:red;'>This class is full. Please choose another class.</p>";
                    } else {
                        // Check if student has already taken the course
                        $alreadyTakenQuery = "
                            SELECT grade 
                            FROM past_enrollments 
                            WHERE student_id = '$studentID' AND course_code = '$courseCode'
                        ";
                        $alreadyTakenResult = mysqli_query($conn, $alreadyTakenQuery);

                        $canEnroll = true;
                        if (mysqli_num_rows($alreadyTakenResult) > 0) {
                            $grade = mysqli_fetch_assoc($alreadyTakenResult)['grade'];
                            if ($grade > 0) {
                                echo "<p style='color:red;'>You have already passed this course and cannot re-enroll.</p>";
                                $canEnroll = false;
                            } elseif ($grade == 0) {
                                echo "<p style='color:blue;'>You are retaking this course.</p>";
                                $canEnroll = true;
                            }
                        }

                        // Proceed if eligible
                        if ($canEnroll) {
                            $checkEnrollmentQuery = "
                                SELECT * 
                                FROM students_classes 
                                WHERE student_id = '$studentID' AND offering_code = '$offeringCode'
                            ";
                            $enrollmentResult = mysqli_query($conn, $checkEnrollmentQuery);

                            if (mysqli_num_rows($enrollmentResult) == 0) {
                                // Enroll student and update enrolled count
                                $insertQuery = "
                                    INSERT INTO students_classes (student_id, offering_code) 
                                    VALUES ('$studentID', '$offeringCode')
                                ";
                                if (mysqli_query($conn, $insertQuery)) {
                                    $updateEnrollmentQuery = "
                                        UPDATE section_offerings 
                                        SET enrolled_students = enrolled_students + 1 
                                        WHERE offering_code = '$offeringCode'
                                    ";
                                    mysqli_query($conn, $updateEnrollmentQuery);

                                    echo "<p style='color:green;'>Class added successfully!</p>";
                                } else {
                                    echo "<p style='color:red;'>Error adding class: " . mysqli_error($conn) . "</p>";
                                }
                            } else {
                                echo "<p style='color:red;'>You are already enrolled in this class.</p>";
                            }
                        }
                    }
                } else {
                    echo "<p style='color:red;'>Invalid offering code. Please try again.</p>";
                }
            }
        ?>

        <div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display: flex; align-items: center; gap: 10px;">
                <label for="offeringCode">Class code to add:</label>
                <input type="text" name="offeringCode" placeholder="ex: 1234" required>
                <button type="submit" class="main-button addclass-btn">Add Class</button> 
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
