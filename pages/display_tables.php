<!-- 
    display_tables.php
    Functions for displaying full db tables

    Last updated: November 29, 2024 | 9:44PM by Jeremiah Ang

    TODO: 
        DONE: View all courses
        DONE: View all offerings
        PENDING: View current profs
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

    <tr>
    <?php
    if ($result->num_rows > 0) {
        // Success: Show table

        while($row = $result->fetch_assoc()){
            echo "<tr>";
            $course_code = $row["course_code"];
            
            echo "<td>" . $row["course_code"] . "</td>";
            echo "<td>" . $row["course_title"] . "</td>";
            echo "<td>" . $row["units"] . "</td>";
            echo "<td>" . (isset($row["co_requisite"]) ? $row["co_requisite"] :  "") . "</td>";

            // Handle prerequisites
            $prereq_query = "SELECT prerequisite FROM prerequisites WHERE course_code = '$course_code'"; 
            $prereq_result = $conn->query($prereq_query); 
            
            $prerequisites = []; 
            if ($prereq_result->num_rows > 0) 
                while ($prereq_row = $prereq_result->fetch_assoc()) { 
                    $prerequisites[] = htmlspecialchars($prereq_row['prerequisite']); 
                }	 
  
            echo "<td>" . implode(", ", $prerequisites) . "</td>";
        }

    }

    else {
        // Failure: Show error message
        echo "<p style='color:red;'>No offerings available.</p>";
    }
    $conn->close();

}      
?>
</table>
        



<?php
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

    <tr>
    <?php
    if ($result->num_rows > 0) {
        // Success: Show table
        echo "<p>$result->num_rows offerings available!</p>";

        while($row = $result->fetch_assoc()){
        ?>
            <?php 
            $color = ($row["enrolled_students"] != $row["enroll_cap"]) ? 'green' : '#00bfff';
            ?>
            <td><?php echo "<font color=$color><b>".$row["offering_code"]."</b></font>";?></td>
            <td><?php echo "<font color=$color><b>".$row["course_code"]."</b></font>";?></td>
            <td><?php echo "<font color=$color><b>".$row["section"]."</b></font>";?></td>
            <td><?php echo $row["class_days"];?></td>
            <td><?php echo $row["class_start_time"];?></td>
            <td><?php echo $row["class_end_time"];?></td>
            <td><?php echo $row["enroll_cap"];?></td>
            <td><?php echo $row["enrolled_students"];?></td>
            <td><?php echo $row["professor"];?></td>
            <td><?php echo $row["room"];?></td>
            </tr>

        <?php
        }
        ?>
        </table>
        
    <?php
    } else {
        // Failure: Show error message
        echo "<p style='color:red;'>No offerings available.</p>";
    }
    $conn->close();
}      
?>
