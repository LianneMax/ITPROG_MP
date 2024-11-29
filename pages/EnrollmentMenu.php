<html>
<head>
    <title>ENROLLMENT</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- Top Panel with ITmosys centered -->
    <div class="top-panel" style="background-color: #507c93;">
    <h1 class="itmosys-header">ITmosys</h1>
</div>

    <div class="enrollment_container">
        <!-- Enrollment header in the top-left of the box -->
        <h2 class="title-header">ENROLLMENT MENU</h2>

        <!-- Line below the Enrollment header -->
        <div class="separator"></div>

        <div class="enrollment-btn">
            <!-- Apply the 'main-button' class to style the buttons consistently -->
            <button class="main-button" onclick="window.location.href='add_class.php'">Add Classes</button>
            <button class="main-button" onclick="window.location.href='drop_class.php'">Remove Classes</button>
            <button class="main-button" onclick="window.location.href='CourseOfferings.php'">Course Offerings</button>
            <button class="main-button" onclick="window.location.href='ViewEAF.php'">View EAF</button>
        </div>
    </div>

    <!-- Logout button -->
    <button class="main-button logout-btn" onclick="window.location.href='LogoutPage.php'">Logout</button>

</body>
</html>
