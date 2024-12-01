<!-- 
    admin_create_profs.php
    Create course offerings via uploading well-formed xml files

    Last updated: November 30, 2024 | 2:59AM by Lianne Balbastro

    TODO: 
        **Polish SQL Error handling**

        PENDING: Manual Input Option        
        DONE: View current profs

 -->

 <?php
include "../includes/dbconfig.php";
include "display_tables.php";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['xml'])) {
        // Handle XML upload
        $xmlFile = $_FILES['xml']['tmp_name'];
        if (file_exists($xmlFile)) {
            $xml = simplexml_load_file($xmlFile);
            foreach ($xml->professor as $prof) {
                $prof_name = $conn->real_escape_string(trim($prof->prof_fullname));
                $sql = "INSERT INTO professors (prof_fullname) VALUES ('$prof_name')
                        ON DUPLICATE KEY UPDATE prof_fullname = VALUES(prof_fullname)";
                $conn->query($sql);
            }
            echo "<div class='success-message'>XML file uploaded successfully!</div>";
        } else {
            echo "<div class='error-message'>Failed to upload XML file.</div>";
        }
    } elseif (isset($_POST['add_prof'])) {
        // Add professor manually
        $prof_name = $conn->real_escape_string(trim($_POST['prof_name']));
        $sql = "INSERT INTO professors (prof_fullname) VALUES ('$prof_name')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='success-message'>Professor added successfully!</div>";
        } else {
            echo "<div class='error-message'>Error: " . $conn->error . "</div>";
        }
    } elseif (isset($_POST['delete_prof'])) {
        // Delete professor
        $prof_name = $conn->real_escape_string(trim($_POST['prof_name']));
        $sql = "DELETE FROM professors WHERE prof_fullname = '$prof_name'";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='success-message'>Professor deleted successfully!</div>";
        } else {
            echo "<div class='error-message'>Error: " . $conn->error . "</div>";
        }
    }
}
?>

<html>
<head>
    <title>Admin | Create Professors</title>
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
        <span class="link-text">Create Professors</span>
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

<!-- Main Content -->
<div class="content">
    <div class="AdminContainer">
        <h2 class="title-header">Create Professors</h2>
        <div class="separator"></div>

        <!-- XML Upload Form -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="file-input-container">
                <label for="xml">XML File:</label>
                <input type="file" id="xml" name="xml" required>
                <button type="submit" class="main-button admin-button">Upload</button>
            </div>
        </form>

        <!-- Manual Add/Edit/Delete -->
        <div class="offerings-container">   

        <!-- Add Professor Form -->
        <div class="form-container">
            <form method="POST" action="">
                <h4>Add Professor</h4>
                <label for="prof_name">Professor Name:</label>
                <input type="text" id="prof_name" name="prof_name" placeholder="Enter Professor's Name" required>
                <button type="submit" name="add_prof" class="main-button admin-button" style="grid-column: span 2;">Add Professor</button>
            </form>
        </div>

        <!-- Professors Table -->
        <div class="table-container">
            <h4>Current Professors</h4>
            <?php
            // Use the displayProfs function to render the table
            displayProfs($conn);
            ?>
        </div>
    </div>
</div>

<script src="../includes/main.js"></script>
</body>
</html>

















