<!-- 
    admin_menu.php
    Central menu for admin options
    This will be the first page the admin sees after logging in

    Last updated: November 29, 2024, 2:13PM by Jeremiah Ang

    TODO: 
        PENDING - Fix admenu-btn in admin.css (buttons dont have gaps between themselves)
 -->
<html>
<head>
    <title>Admin Menu</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navigation.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<!-- Top Panel -->
<div class="top-panel admin-top-panel">
    <h1 class="itmosys-header">ITmosys | Admin</h1>
</div>

    <div class="container">
        <!-- Enrollment header in the top-left of the box -->
        <h2 class="title-header">Admin Menu</h2>

        <!-- Line below the Enrollment header -->
        <div class="separator"></div>
        
        <!-- <div class="admenu-btn"> admenu-btn created in admin.css Line 53 -->
            <!-- Apply the 'main-button' class to style the buttons consistently --> 
            <button class="main-button admin-button" onclick="window.location.href='admin_add_courses.php'">Create Courses</button> 
            <button class="main-button admin-button" onclick="window.location.href='admin_create_offerings.php'">Create Offerings</button> 
            <button class="main-button admin-button" onclick="window.location.href='admin_create_profs.php'">Create Profs</button> 
            <button class="main-button admin-button" onclick="window.location.href='admin_summary_report.php'">Summary Report</button>
        </div>

    </div>

    <!-- Logout button -->
    <button class="main-button logout-btn" onclick="window.location.href='LogoutPage.php'">Logout</button>

</body>
</html>

