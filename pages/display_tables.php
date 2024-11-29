<!-- 
    display_tables.php
    Functions for displaying full db tables

    Last updated: November 30, 2024 | 3:15AM by Lianne Balbastro

    TODO: 
        DONE: View all courses
        DONE: View all offerings
        DONE: View current profs
 -->

 <?php
/**
 * This function displays all the courses, including their prerequisites.
 */
function displayCourses($conn){
    // Query to check for all records of courses
    $sql = "SELECT * FROM courses";
    $result = $conn->query($sql);
    ?>

    <table>
    <tr>
        <th>COURSE CODE</th>
        <th>COURSE TITLE</th>
        <th>UNITS</th>
        <th>COREQUISITE</th>
        <th>PREREQUISITE</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        // Success: Show table
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            $course_code = $row["course_code"];
            
            echo "<td>" . $row["course_code"] . "</td>";
            echo "<td>" . $row["course_title"] . "</td>";
            echo "<td>" . $row["units"] . "</td>";
            echo "<td>" . (isset($row["co_requisite"]) ? $row["co_requisite"] : "") . "</td>";

            // Handle prerequisites
            $prereq_query = "SELECT prerequisite FROM prerequisites WHERE course_code = '$course_code'"; 
            $prereq_result = $conn->query($prereq_query); 
            
            $prerequisites = []; 
            if ($prereq_result->num_rows > 0) {
                while ($prereq_row = $prereq_result->fetch_assoc()) { 
                    $prerequisites[] = htmlspecialchars($prereq_row['prerequisite']); 
                }
            }
  
            echo "<td>" . implode(", ", $prerequisites) . "</td>";
            echo "</tr>";
        }
    } else {
        // Failure: Show error message
        echo "<tr><td colspan='5' style='color:red;'>No courses available.</td></tr>";
    }
    ?>
    </table>
    <?php
}

/**
 * This function displays all the course offerings.
 */
function displayOfferings($conn){
    // Query to check for all records of class offerings
    $sql = "SELECT * FROM section_offerings";
    $result = $conn->query($sql);
    ?>

    <table>
    <tr>
        <th>Code</th>
        <th>Class</th>
        <th>Section</th>
        <th>Class Days</th>
        <th>Class Start Time</th>
        <th>Class End Time</th>
        <th>Enroll Cap</th>
        <th>Enrolled</th>
        <th>Professor</th>
        <th>Room</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        // Success: Show table
        while($row = $result->fetch_assoc()){
            $color = ($row["enrolled_students"] != $row["enroll_cap"]) ? 'green' : '#00bfff';
            echo "<tr>";
            echo "<td><font color=$color><b>" . $row["offering_code"] . "</b></font></td>";
            echo "<td><font color=$color><b>" . $row["course_code"] . "</b></font></td>";
            echo "<td><font color=$color><b>" . $row["section"] . "</b></font></td>";
            echo "<td>" . $row["class_days"] . "</td>";
            echo "<td>" . $row["class_start_time"] . "</td>";
            echo "<td>" . $row["class_end_time"] . "</td>";
            echo "<td>" . $row["enroll_cap"] . "</td>";
            echo "<td>" . $row["enrolled_students"] . "</td>";
            echo "<td>" . $row["professor"] . "</td>";
            echo "<td>" . $row["room"] . "</td>";
            echo "</tr>";
        }
    } else {
        // Failure: Show error message
        echo "<tr><td colspan='10' style='color:red;'>No offerings available.</td></tr>";
    }
    ?>
    </table>
    <?php
}

/**
 * This function displays all professors.
 */
function displayProfs($conn){
    // Query to fetch all professors
    $sql = "SELECT prof_fullname FROM professors";
    $result = $conn->query($sql);
    ?>

    <table>
    <tr>
        <th>Professor Name</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        // Success: Display table rows
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["prof_fullname"]) . "</td>";
            echo "</tr>";
        }
    } else {
        // Failure: Show error message
        echo "<tr><td style='color:red;'>No professors found.</td></tr>";
    }
    ?>
    </table>
    <?php
}
?>

