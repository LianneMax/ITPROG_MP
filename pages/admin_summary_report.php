<html>
    <head>
        <title>Admin | Summary Report</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/navigation.css">
        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>

    <body>

    <?php include 'admin_SidebarTopPanel.php'; ?>

        <!-- Main Body -->
        <div class="content">
        <div class="AdminContainer">

            <!-- Enrollment Date Selection -->
            <h2>Select a Date</h2>

            <form method="post" action="">
                <label for="year">Year:</label>
                <select name="year" id="year">
                    <?php
                    $currentYear = date('Y');
                    for ($i = $currentYear; $i >= 2000; $i--) {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>

                <label for="month">Month:</label>
                <select name="month" id="month">
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                        echo "<option value='$m'>" . date('F', mktime(0, 0, 0, $m, 10)) . "</option>";
                    }
                    ?>
                </select>

                <label for="day">Day:</label>
                <select name="day" id="day">
                    <?php
                    for ($d = 1; $d <= 31; $d++) {
                        echo "<option value='$d'>$d</option>";
                    }
                    ?>
                </select>
                
                <div>
                    <input type="submit" class="AdminLogin_button" value="Submit"> 
                </div>
                
            </form>

            <div class="table-container"> 

                <!-- Stores the year, date, and month into a single string -->
                <?php
                $selection_done = 0;
                $selected_date = '';

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $year = $_POST['year'];
                    $month = $_POST['month'];
                    $day = $_POST['day'];

                    //Combining the selected date into one string
                    $selected_date = "$year-$month-$day";
                    $selection_done = 1;
                }

                if ($selection_done==1) {
                    echo "<h3>Enrollment Summary Report for $selected_date</h3>";
                }
                ?>

                <!-- Enrollment Summary Table -->
                <?php
                include "../includes/dbconfig.php";

                // Create database connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check database connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $getSubjects = "
                    SELECT course_code, COUNT(DISTINCT student_id) AS unique_students_count
                    FROM past_enrollments
                    WHERE date_enrolled = '$selected_date'
                    GROUP BY course_code
                ";

                // Execute the query
                $result = $conn->query($getSubjects);

                // Display the result in a table
                if ($selection_done==1) {
                    if ($result->num_rows > 0) {
                        echo "<table border='1'><tr><th>Course Code</th><th>Number of Unique Students</th></tr>";
    
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr><td>" . $row['course_code'] . "</td><td>" . $row['unique_students_count'] . "</td></tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No data found for the selected date.";
                    }
                } else if($selection_done==0){
                    echo "Input desired enrollment date to view.";
                }

                // Close the database connection
                $conn->close();
                ?>
            </div>
        </div>
    </div>
    <script src="../includes/main.js"></script>
    </body>
</html>