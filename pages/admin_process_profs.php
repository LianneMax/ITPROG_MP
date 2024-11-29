<!-- 
    admin_process_professors.php
    This page inserts the professor XML File Contents if valid 

    Last updated: November 29, 2024 | 4:30PM by Jeremiah Ang

    TODO: 
        - View current profs
 -->

<html>
<head>
    <title>Process New Professors</title>
    <link rel="stylesheet" href="../assets/css/admin.css"> <!-- Link to the CSS file -->
</head>
<body>
    <?php
    include "../includes/dbconfig.php";
    session_start();

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        exit("POST request method required");
    }

    // Check if files are uploaded
    if (empty($_FILES)) {
        exit("$_FILES is empty. Enable file_uploads (file_uploads=On) in C:\\xampp\\php\\php.ini");
    }

    // Check for file upload errors
    if ($_FILES["xml"]["error"] !== UPLOAD_ERR_OK) {
        switch ($_FILES["xml"]["error"]) {
            case UPLOAD_ERR_PARTIAL:
                exit("File only partially uploaded");
            case UPLOAD_ERR_NO_FILE:
                exit("No file was uploaded");
            case UPLOAD_ERR_CANT_WRITE:
                exit("Failed to write file");
            case UPLOAD_ERR_NO_TMP_DIR:
                exit("Temp folder not found");
            default:
                exit("Unknown file error");
        }
    }

    // Restrict file type to XML only
    $mime_types = ["text/xml", "application/xml"];
    if (!in_array($_FILES["xml"]["type"], $mime_types)) {
        exit("Invalid file type");
    }

    // Copy the file path
    $filepath = $_FILES["xml"]["tmp_name"];

    // Load the XML file
    $xml = simplexml_load_file($filepath) or die("Error: cannot create object!");

    ?>
    <!-- Table of XML Contents -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>prof_fullname</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($xml->professor as $professor) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($professor['prof_fullname']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    // Loop through each professor and insert into the database
    foreach ($xml->professor as $professor) {
        $prof_fullname = $conn->real_escape_string($professor['prof_fullname']);

        // Insert professor details into `professors` table
        $insertQuery = "INSERT INTO professors (prof_fullname) 
                        VALUES ('$prof_fullname')";
                             
        if (!mysqli_query($conn, $insertQuery)) {
            echo "<p class='error-message'>Error adding professor '$prof_fullname': " . mysqli_error($conn) . "</p>";
            continue;
        }

        echo "<p class='success-message'>Prof. '$prof_fullname' added successfully!</p>";
    }

    // Close the connection
    $conn->close();
    ?>
</body>
</html>


