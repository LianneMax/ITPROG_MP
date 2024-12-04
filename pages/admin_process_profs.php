<?php
session_start();
include "../includes/dbconfig.php";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize messages and table variable
$_SESSION['message'] = "";
$uploadedTable = "";

// Handle XML upload if a file is provided
if (isset($_FILES['xml']) && $_FILES['xml']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['xml']['error'] === UPLOAD_ERR_OK) {
        $mime_types = ["text/xml", "application/xml"];
        if (in_array($_FILES['xml']['type'], $mime_types)) {
            $xmlFile = $_FILES['xml']['tmp_name'];
            if (file_exists($xmlFile)) {
                $xml = simplexml_load_file($xmlFile);

                // Check if XML is valid
                if ($xml && isset($xml->professor)) {
                    // Start building the table
                    $uploadedTable = '<table>
                                        <thead>
                                            <tr>
                                                <th>Professor Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

                    foreach ($xml->professor as $prof) {
                        // Ensure the professor has a name
                        $prof_name = isset($prof->prof_fullname) ? trim((string)$prof->prof_fullname) : "";

                        // Add the professor name to the table
                        $uploadedTable .= "<tr><td>" . htmlspecialchars($prof_name) . "</td></tr>";

                        if (!empty($prof_name)) {
                            $escaped_prof_name = $conn->real_escape_string($prof_name);

                            // Check for duplicates in the database
                            $checkDuplicateQuery = "SELECT * FROM professors WHERE prof_fullname = '$escaped_prof_name'";
                            $duplicateResult = $conn->query($checkDuplicateQuery);

                            if ($duplicateResult && $duplicateResult->num_rows > 0) {
                                $_SESSION['message'] .= "<div class='error-message'>Duplicate entry: Professor '$prof_name' already exists in the database.</div>";
                            } else {
                                // Insert into database
                                $sql = "INSERT INTO professors (prof_fullname) VALUES ('$escaped_prof_name')";
                                if ($conn->query($sql) === TRUE) {
                                    $_SESSION['message'] .= "<div class='success-message'>Professor '$prof_name' added successfully!</div>";
                                } else {
                                    $_SESSION['message'] .= "<div class='error-message'>Error adding professor '$prof_name': " . $conn->error . "</div>";
                                }
                            }
                        } else {
                            $_SESSION['message'] .= "<div class='error-message'>Invalid or empty professor name in XML file.</div>";
                        }
                    }

                    $uploadedTable .= '</tbody></table>';
                } else {
                    $_SESSION['message'] = "<div class='error-message'>Invalid XML structure.</div>";
                }
            } else {
                $_SESSION['message'] = "<div class='error-message'>Failed to upload XML file. File does not exist.</div>";
            }
        } else {
            $_SESSION['message'] = "<div class='error-message'>Invalid file type. Only XML files are allowed.</div>";
        }
    } else {
        $_SESSION['message'] = "<div class='error-message'>Error uploading file. Please try again.</div>";
    }
}

// Handle manual add
if (isset($_POST['add_prof']) && isset($_POST['prof_name'])) {
    $prof_name = $conn->real_escape_string(trim($_POST['prof_name']));
    if (!empty($prof_name)) {
        // Check for duplicates in the database
        $checkDuplicateQuery = "SELECT * FROM professors WHERE prof_fullname = '$prof_name'";
        $duplicateResult = $conn->query($checkDuplicateQuery);

        if ($duplicateResult && $duplicateResult->num_rows > 0) {
            $_SESSION['message'] .= "<div class='error-message'>Duplicate entry: Professor '$prof_name' already exists in the database.</div>";
        } else {
            // Insert into the database
            $sql = "INSERT INTO professors (prof_fullname) VALUES ('$prof_name')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] .= "<div class='success-message'>Professor '$prof_name' added successfully!</div>";
            } else {
                $_SESSION['message'] .= "<div class='error-message'>Error: " . $conn->error . "</div>";
            }
        }
    } else {
        $_SESSION['message'] .= "<div class='error-message'>Professor name cannot be empty.</div>";
    }
}

// Handle professor deletion
if (isset($_POST['delete_prof']) && isset($_POST['prof_name'])) {
    $prof_name = $conn->real_escape_string(trim($_POST['prof_name']));

    // Check if the professor is assigned to any course offerings
    $checkAssignmentsQuery = "SELECT * FROM section_offerings WHERE professor = '$prof_name'";
    $assignmentsResult = $conn->query($checkAssignmentsQuery);

    if ($assignmentsResult && $assignmentsResult->num_rows > 0) {
        $_SESSION['message'] .= "<div class='error-message'>Cannot delete professor '$prof_name'. They are assigned to one or more course offerings.</div>";
    } else {
        // Proceed to delete the professor
        $deleteQuery = "DELETE FROM professors WHERE prof_fullname = '$prof_name'";
        if ($conn->query($deleteQuery) === TRUE) {
            $_SESSION['message'] .= "<div class='success-message'>Professor '$prof_name' deleted successfully!</div>";
        } else {
            $_SESSION['message'] .= "<div class='error-message'>Error deleting professor '$prof_name': " . $conn->error . "</div>";
        }
    }
}

?>

<html>
<head>
    <title>Admin | Manage Courses</title>
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
        <h2 class="title-header">Manage Courses</h2>
        <div class="separator"></div>
</head>
<body>
    <!-- Display Messages -->
    <?php if (!empty($_SESSION['message'])): ?>
        <div>
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Uploaded XML Table -->
    <?php if (!empty($uploadedTable)): ?>
        <div class="table-container">
            <?php echo $uploadedTable; ?>
        </div>
    <?php endif; ?>

    <!-- Back Button -->
    <div class="back-button">
        <button onclick="window.location.href='admin_create_profs.php'" class="main-button admin-button">Back</button>
    </div>
</body>
</html>









