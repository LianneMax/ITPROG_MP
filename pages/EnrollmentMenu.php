<html>
<head>
    <title>ENROLLMENT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background-image: url('../pages/Client.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">


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
    <i class="fas fa-sign-out-alt logout_icon" onclick="window.location.href='LogoutPage.php'" title="Logout" 
   style="font-size: 40px; position: absolute; bottom: 20px; right: 20px; color: #FFFFFF; cursor: pointer;"></i>


</body>
</html>

