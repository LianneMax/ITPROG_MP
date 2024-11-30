<!-- 
    admin_summary_report.php
    generate a summary report for the enrolled students in the courses

    Last updated: November 30, 2024 by Charles Duelas

    TODO: 
        PENDING - make the sql query to retrieve the student count
        PENDING - make the table to present the database values
        PENDING - make the form to input chosen enrollment date
        PENDING - display the chosen enrollment date by the user
        PENDING - display the subjects enrolled in the chosen date
 -->

<html>

    <head>
        <title>Admin | Add Courses</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/navigation.css">
        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>

    <body>

        <!-- Hamburger Menu Button -->
        <div id="hamburger" class="hamburger">
            <i class="fas fa-bars"></i>
        </div>

        <!-- Sidebar -->
        <div id="sidebar" class="sidebar admin-sidebar">
            <div class="separator" style="margin-top: 50px;"></div>
            <button class="sidebar-btn" onclick="window.location.href='admin_create_courses.php'">
                <i class="fas fa-book"></i>
                <span class="link-text">Create Courses</span>
            </button>
            <button class="sidebar-btn" onclick="window.location.href='admin_create_offerings.php'">
                <i class="fas fa-chalkboard-teacher"></i>
                <span class="link-text">Create Offerings</span>
            </button>
            <button class="sidebar-btn" onclick="window.location.href='admin_create_profs.php'">
                <i class="fas fa-user-tie"></i>
                <span class="link-text">Create Profs</span>
            </button>
            <button class="sidebar-btn" onclick="window.location.href='admin_summary_report.php'">
                <i class="fas fa-chart-bar"></i>
                <span class="link-text">Summary Report</span>
            </button>
            <div class="separator" style="margin-top: 10px;"></div>
            <!-- Logout Button -->
            <button class="logout-btn" onclick="window.location.href='LogoutPage.php'">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>

        <!-- Top Panel -->
        <div class="top-panel admin-top-panel">
            <h1 class="itmosys-header">ITmosys | Admin</h1>
        </div>

        <!-- Main Body -->
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
                <input type="submit" class="AdminLogin_button" value="Submit">
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $year = $_POST['year'];
                $month = $_POST['month'];
                $day = $_POST['day'];
                
                echo "<h3>Selected Enrollment Date: $year-$month-$day</h3>";
            }
            ?>

            <!-- Enrollment Summary Table -->
            <h2>Enrollment Summary Report for <?php echo "$year-$month-$day"; ?></h2>
        </div>
    </body>
</html>