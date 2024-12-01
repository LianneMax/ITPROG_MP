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
 * Includes Edit and Delete actions for each row.
 */
function displayCourses($conn){
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
            <th>ACTIONS</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
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

                // Add action buttons
                echo "<td>
                        <form method='POST' action='admin_process_courses.php' style='display: inline;'>
                            <button type='submit' name='edit_course' value='$course_code' class='main-button admin-button'>Edit</button>
                        </form>
                        <form method='POST' action='admin_process_courses.php' style='display: inline;'>
                            <button type='submit' name='delete_course' value='$course_code' class='main-button admin-button'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='color:red;'>No courses available.</td></tr>";
        }
        ?>
    </table>
    <?php
}

/**
 * This function displays all the course offerings.
 * Includes Edit and Delete actions for each row.
 */
function displayOfferings($conn){
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
            <th>ACTIONS</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
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

                // Add action buttons
                echo "<td>
                        <form method='POST' action='admin_process_offerings.php' style='display: inline;'>
                            <button type='submit' name='edit_offering' value='" . htmlspecialchars($row['offering_code']) . "' class='main-button admin-button'>Edit</button>
                        </form>
                        <form method='POST' action='admin_process_offerings.php' style='display: inline;'>
                            <button type='submit' name='delete_offering' value='" . htmlspecialchars($row['offering_code']) . "' class='main-button admin-button'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11' style='color:red;'>No offerings available.</td></tr>";
        }
        ?>
    </table>
    <?php
}

/**
 * This function displays all professors.
 * Includes Edit and Delete actions for each row.
 */
function displayProfs($conn){
    $sql = "SELECT prof_id, prof_fullname FROM professors"; // Assuming prof_id exists
    $result = $conn->query($sql);
    ?>

    <table>
        <tr>
            <th>Professor ID</th>
            <th>Professor Name</th>
            <th>ACTIONS</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["prof_id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["prof_fullname"]) . "</td>";

                // Add action buttons
                echo "<td>
                        <form method='POST' action='admin_process_profs.php' style='display: inline;'>
                            <button type='submit' name='edit_prof' value='" . htmlspecialchars($row['prof_id']) . "' class='main-button admin-button'>Edit</button>
                        </form>
                        <form method='POST' action='admin_process_profs.php' style='display: inline;'>
                            <button type='submit' name='delete_prof' value='" . htmlspecialchars($row['prof_id']) . "' class='main-button admin-button'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3' style='color:red;'>No professors found.</td></tr>";
        }
        ?>
    </table>
    <?php
}
?>
