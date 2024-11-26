<!-- 
    admin_process_course.php
    This page inserts the XML File Contents if valid 

    Last updated: November 26, 2024, 4:00PM by Jeremiah Ang

    TODO: Fix User interface (Pls put UI elements in one include file, to avoid copy pasting sidebars, buttons, etc)
          Sidebar must contain admin-specific options
 -->

    <?php
        include "../includes/dbconfig.php";
        session_start();

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    ?>
    
<?php

if($_SERVER["REQUEST_METHOD"] !== "POST"){
    exit("POST request method required");
}

// Uncomment this to check where is the php.ini file on your machine
// phpinfo(); 
if(empty($_FILES)){
    exit("$_FILES is empty. Enable file_uploads (file_uploads=On) in C:\xampp\php\php.ini");
}

// Error checking
if($_FILES["xml"]["error"] !== UPLOAD_ERR_OK){
    switch($_FILES["xml"]["error"]){
        case UPLOAD_ERR_PARTIAL:
            exit("File only partially uploaded");
            break;
        case UPLOAD_ERR_NO_FILE:
            exit("No file was uploaded");
            break;
        case UPLOAD_ERR_CANT_WRITE:
            exit("Failed to write file");
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            exit("Temp folder not found");
            break;
        default:
            exit("unknown file error.");
            break;
    }

}

//Restrict file type to XML only
$mime_types = ["text/xml", "application/xml"];

if(!in_array($_FILES["xml"]["type"], $mime_types)){
    exit("Invalid file type");
}

//Copy filepath
$filepath = $_FILES["xml"]["tmp_name"];
echo $filepath;

// print_r($_FILES);
?>

<!-- Table of XML Contents -->
<table border="1" width="50%">
<tr bgcolor="#FLE5EB">
    <th>COURSE CODE</th>
    <th>COURSE TITLE</th>
    <th>UNITS</th>
    <th>COREQUISITE</th>
    <th>PREREQUISITE</th>
</tr>


<?php
// Load the XML file contents
$xml = simplexml_load_file($filepath) or die ("Error cannot create object!");

// Print XML contents
for($x=0; $x < sizeof($xml->course); $x++) {
    echo "<tr>";
    echo "<td>". $xml->course[$x]['course_code']. "</td>";
    echo "<td>". $xml->course[$x]->course_title. "</td>";
    echo "<td>". $xml->course[$x]->units. "</td>";

    // Check if there is a co_requisite
    echo "<td>". (isset($xml->course[$x]->co_requisite) ? $xml->course[$x]->co_requisite : ""). "</td>";

    // Print multiple prerequisites, if any
    echo "<td>";
    $prerequisites = $xml->course[$x]->prerequisite;
    $prereqArray = [];
    for($y=0; $y < sizeof($prerequisites); $y++) {
        $prereqArray[] = $prerequisites[$y];
    }
    echo implode(", ", $prereqArray);
    echo "</td>";

    echo "</tr>";
}
?>

</table>

<?php

// Load the XML file contents
$xml = simplexml_load_file($filepath) or die("Error: cannot create object!");

// Loop through each course element
for($x = 0; $x < sizeof($xml->course); $x++) {
    $course_code = $xml->course[$x]['course_code'];

    // Insert course code into course_codes table
    $insertQuery = "
    INSERT INTO course_codes (course_code) 
    VALUES ('$course_code')
    ";

    if (mysqli_query($conn, $insertQuery)) {
        echo "<p style='color:green;'>Unique Course Code added successfully!</p>";

        $course_title = $xml->course[$x]->course_title;
        $units = (int)($xml->course[$x]->units);
        $co_requisite = isset($xml->course[$x]->co_requisite) ? $xml->course[$x]->co_requisite : '';

        // Insert course details into courses table
        $insertQuery = "
        INSERT INTO courses (course_code, course_title, units, co_requisite)
        VALUES ('$course_code', '$course_title', $units, '$co_requisite')
        ";

        if(mysqli_query($conn, $insertQuery)){
            // Insert multiple prerequisites, if any
            $prerequisites = $xml->course[$x]->prerequisite;

            for($y = 0; $y < sizeof($prerequisites); $y++) {
                $prerequisite = $prerequisites[$y];
                $insertQuery = "
                INSERT INTO prerequisites (course_code, prerequisite)
                VALUES ('$course_code', '$prerequisite')
                ";
                if(!mysqli_query($conn, $insertQuery)){
                    echo "<p style='color:red;'>Error adding prerequisite: " . mysqli_error($conn) . "</p>";
                }
            }
            echo "<p style='color:green;'>Course and prerequisites added successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error adding course: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Error adding course code: " . mysqli_error($conn) . "</p>";
    }
}
?>
