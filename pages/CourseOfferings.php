<html>
<head>
    <title>COURSE OFFERINGS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    
</head>
<body>

    <!-- ITmosys Header -->
    <h1 class="itmosys-header">ITmosys</h1>

    <div class="courseOffer_container">
        <!-- Title header in the top-left of the box -->
        <h2 class="title-header">Course Offerings</h2>

        <!-- Line below the Title header -->
        <div class="separator"></div>

    <div>
        <?php
        echo "<h3><font color='sky blue'>BLUE &emsp;&ensp;</font>Closed Section</h3>";
        echo "<h3><font color='green'>GREEN &emsp;</font>Open Section</h3>";
        ?>
    </div>

    <div>
    <form method="post" action="">
        <label for="courseCode">Enter course code:</label>
        <input type='text' name='courseCode' placeholder="ex: IT-PROG"> <br /><br />
        <button type="submit" class="search">Search</button>
    </form>

    <?php

        include "../includes/dbconfig.php";
        session_start(); //starts the session

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['courseCode'])) {
            // Fetching form data
            $courseCode = mysqli_real_escape_string($conn, $_POST['courseCode']);

            // Store the offering code in a session variable
            $_SESSION['offeringCode'] = $courseCode;

            // Query to check for records of a class offering
            $sql = "SELECT * FROM section_offerings WHERE course_code = '$courseCode'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Success: Show table
                echo "<p>$result->num_rows $courseCode offerings available!</p>";
                header("refresh:1;url=CourseOfferings.php");
            } else {
                // Failure: Show error message
                echo "<p style='color:red;'>No $courseCode offerings available.</p>";
            }
            
            // Close connection
            $conn->close();
        }
    ?>
</div>


</body>
</html>
