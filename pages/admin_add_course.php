<html>
<head>
    <title>Admin | Add Course</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navigation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Form for uploading files -->
    <form action="admin_process_course.php"
    method="post"
    enctype="multipart/form-data">

    <label for="xml">XML file</label>
    <input type="file" id="xml" name="xml">

    <button>Upload</button>
    </form>
    <!-- Form for uploading files -->

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

<script src="../includes/main.js"></script>
</body>
</html>


