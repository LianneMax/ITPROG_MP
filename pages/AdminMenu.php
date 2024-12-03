<!-- 
    admin_menu.php
    Central menu for admin options
    This will be the first page the admin sees after logging in

    Last updated: November 30, 2024, 3:30AMAM by Lianne Balbastro

    TODO: 
        DONE: Fix admenu-btn in admin.css (buttons dont have gaps between themselves)
 -->
<html>
<head>
    <title>Admin Menu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body style="background-image: url('../pages/Server.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

</div>

<!-- Main Container -->
<div class="enrollment_container">
    <!-- Admin Menu Header -->
    <h2 class="title-header">ADMIN MENU</h2>

    <!-- Separator -->
    <div class="separator"></div>

    <!-- Buttons for Admin Menu -->
    <div class="admenu-btn">
        <button class="main-button admin-button" onclick="window.location.href='admin_create_courses.php'">Create Courses</button>
        <button class="main-button admin-button" onclick="window.location.href='admin_create_offerings.php'">Create Offerings</button>
        <button class="main-button admin-button" onclick="window.location.href='admin_create_profs.php'">Create Profs</button>
        <button class="main-button admin-button" onclick="window.location.href='admin_summary_report.php'">Summary Report</button>
    </div>
</div>

    <!-- Logout button -->
    <i class="fas fa-sign-out-alt logout_icon" onclick="window.location.href='LogoutPage.php'" title="Logout" 
   style="font-size: 40px; position: absolute; bottom: 20px; right: 20px; color: #FFFFFF; cursor: pointer;"></i>



</body>
</html>

