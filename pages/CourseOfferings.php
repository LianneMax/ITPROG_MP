<html>
<head>
    <title>COURSE OFFERINGS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navigation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <?php
        include "../includes/dbconfig.php";
        session_start(); //starts the session

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
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
    </div> 

    <!-- Main Content -->
    <div class="content">
        <div class="courseOffer_container">
            <!-- Title header in the top-left of the box -->
            <h2 class="title-header">Course Offerings</h2>

            <!-- Line below the Title header -->
            <div class="separator"></div>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['courseCode'])) {
                // Fetching form data
                $courseCode = mysqli_real_escape_string($conn, $_POST['courseCode']);
                $courseCode = strtoupper($courseCode);

                // Store the offering code in a session variable
                $_SESSION['offeringCode'] = $courseCode;

                // Query to check for records of a class offering
                $sql = "SELECT * FROM section_offerings WHERE course_code = '$courseCode'";
                $result = $conn->query($sql);
                ?>

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

                <tr>
                <?php
                if ($result->num_rows > 0) {
                    // Success: Show table
                    echo "<p>$result->num_rows $courseCode offerings available!</p>";

                    while($row = $result->fetch_assoc()){
                    ?>
                        <?php 
                        $color = ($row["enrolled_students"] != $row["enroll_cap"]) ? 'green' : '#00bfff';
                        ?>
                        <td><?php echo "<font color=$color><b>".$row["offering_code"]."</b></font>";?></td>
                        <td><?php echo "<font color=$color><b>".$row["course_code"]."</b></font>";?></td>
                        <td><?php echo "<font color=$color><b>".$row["section"]."</b></font>";?></td>
                        <td><?php echo $row["class_days"];?></td>
                        <td><?php echo $row["class_start_time"];?></td>
                        <td><?php echo $row["class_end_time"];?></td>
                        <td><?php echo $row["enroll_cap"];?></td>
                        <td><?php echo $row["enrolled_students"];?></td>
                        <td><?php echo $row["professor"];?></td>
                        <td><?php echo $row["room"];?></td>
                        </tr>

                    <?php
                    }
                    ?>
                    </table>
                    
                <?php
                } else {
                    // Failure: Show error message
                    echo "<p style='color:red;'>No $courseCode offerings available.</p>";
                }
                $conn->close();
            }
            ?>

            <div>
                <h3><font color='#00bfff'>BLUE &emsp;&ensp;</font>Closed Section</h3>
                <h3><font color='green'>GREEN &emsp;</font>Open Section</h3>
            </div>

            <div>
                <form method="post" action="" class="courseOffering-form">
                    <label for="courseCode">Enter course code:</label>
                    <input type="text" name="courseCode" placeholder="ex: IT-PROG">
                    <button type="submit" onclick="showTable()" class="courseOffering-button">Search</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showTable() {
            document.getElementById("table").style.display = "table";
        }
    </script>

    <script src="../includes/main.js"></script>

</body>
</html>

