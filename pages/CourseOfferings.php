<html>
<head>
    <title>COURSE OFFERINGS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    
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

    <!-- ITmosys Header -->
    <h1 class="itmosys-header">ITmosys</h1>

    <div class="courseOffer_container">
        <!-- Title header in the top-left of the box -->
        <h2 class="title-header">Course Offerings</h2>

        <!-- Line below the Title header -->
        <div class="separator"></div>



        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['courseCode'])) {
                // Fetching form data
                $courseCode = mysqli_real_escape_string($conn, $_POST['courseCode']);

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
                        <?php if($row["enrolled_students"] != $row["enroll_cap"])
                                $color = 'green';
                        
                            else
                                $color = '#00bfff';
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
                
                // Close connection
                $conn->close();
            }
        ?>

        <div>
            <?php
            echo "<h3><font color='#00bfff'>BLUE &emsp;&ensp;</font>Closed Section</h3>";
            echo "<h3><font color='green'>GREEN &emsp;</font>Open Section</h3>";
            ?>
        </div>

        <div>
        <form method="post" action="">
            <label for="courseCode">Enter course code:</label>
            <input type='text' name='courseCode' placeholder="ex: IT-PROG"> <br /><br />
            <button type="submit" onclick="showTable()" class="search">Search</button>
        </form>


        </div>

        
    </div>

    <script>
        function showTable() {
            document.getElementById("table").style.display = "table";
        }
    </script>
</body>
</html>