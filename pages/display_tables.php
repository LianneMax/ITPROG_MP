<?php
include "../includes/dbconfig.php";

// Start the session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * This function displays all the professors.
 * Includes Delete actions for each row.
 */
function displayProfs($conn) {
    $sql = "SELECT * FROM professors";
    $result = $conn->query($sql);
    ?>

    <table>
        <tr>
            <th>Professor Name</th>
            <th>ACTIONS</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $prof_name = htmlspecialchars($row["prof_fullname"]);
                echo "<tr>";
                echo "<td>" . $prof_name . "</td>";
                echo '<td>
                        <form method="POST" action="admin_process_profs.php" style="display: flex; flex-direction: column; gap: 5px; align-items: center;">
                            <input type="hidden" name="prof_name" value="' . $prof_name . '">
                            <button type="submit" name="delete_prof" class="main-button admin-button">Delete</button>
                        </form>
                      </td>';
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2' style='color:red;'>No professors available.</td></tr>";
        }
        ?>
    </table>
    <?php
}

/**
 * This function displays all the courses, including their prerequisites.
 * Includes Delete actions for each row.
 */
function displayCourses($conn) {
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
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                $course_code = htmlspecialchars($row["course_code"]);
                echo "<td>" . $course_code . "</td>";
                echo "<td>" . htmlspecialchars($row["course_title"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["units"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["co_requisite"] ?? "None") . "</td>";

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

                // Add action button for delete only
                echo '<td>
                        <form method="POST" action="admin_process_courses.php" style="display: flex; flex-direction: column; gap: 5px; align-items: center;">
                            <button type="submit" name="delete_course" value="' . $course_code . '" class="main-button admin-button">Delete</button>
                        </form>
                      </td>';
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
 * Includes Delete actions for each row.
 */
function displayOfferings($conn) {
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
            <th>Actions</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $color = ($row["enrolled_students"] != $row["enroll_cap"]) ? 'green' : '#00bfff';
                echo "<tr>";
                echo "<td><font color=$color><b>" . htmlspecialchars($row["offering_code"]) . "</b></font></td>";
                echo "<td><font color=$color><b>" . htmlspecialchars($row["course_code"]) . "</b></font></td>";
                echo "<td><font color=$color><b>" . htmlspecialchars($row["section"]) . "</b></font></td>";
                echo "<td>" . htmlspecialchars($row["class_days"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["class_start_time"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["class_end_time"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["enroll_cap"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["enrolled_students"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["professor"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["room"]) . "</td>";
                echo '<td>
                        <form method="POST" action="admin_process_offerings.php" style="display: flex; flex-direction: column; gap: 5px; align-items: center;">
                            <input type="hidden" name="offering_code" value="' . htmlspecialchars($row['offering_code']) . '">
                            <button type="submit" name="delete_offering" class="main-button admin-button">Delete</button>
                        </form>
                      </td>';
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11' style='color:red;'>No offerings available.</td></tr>";
        }
        ?>
    </table>
    <?php
}
?>

