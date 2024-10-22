<html>
<head><title>View EAF</title>

    <style>
         @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        .container {
            position: relative;
            text-align: left;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: auto;
            height: auto;
            margin-top: 40px;
        }


        body{
            font-family: 'Montserrat', Times New Roman, serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            position: relative;
        }

        tr, th, td {
            border: 1px solid black;
            border-radius: 10px;
            padding: 5px;
        }

        h1 {
            position: absolute;
            top: 10px;
            font-size: 36px;
            font-family: fantasy;
            color: #5b8ba4; 
            margin-top: 150px;
            z-index: 1;
        }

        h2 {
            margin: 0;
            padding-bottom: 10px;
            color: #333;
            font-family: 'Montserrat', Times New Roman, serif;
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 24px;
        }

        .separator {
            border-top: 2px solid #ccc;
            margin-top: 40px; 
            margin-bottom: 20px; 
        }

        label {
            font-family: 'Montserrat', Times New Roman, serif;
            margin-top: 10px;
            display: block; /* Makes sure the label takes up the entire width */
        }

    </style>

    <?php
    session_start(); //starts the session

    //After turning on SQL on XAMPP, you can manage the DB via this url:
    // http://localhost/phpmyadmin
    $server = "localhost";
    $username = "root"; //will change this later
    $password = "";
    $dbname = "itmosys_db";

    //Create connection
    $conn = new mysqli($server, $username, $password, $dbname);

    if (isset($_SESSION['student_id'])) {
        $student_id = $_SESSION['student_id'];
    }

    $sql = "SELECT s.student_id, s.student_name, c.course_title, so.section, so.class_days, so.class_start_time, so.class_end_time, so.professor, so.room
            FROM students s
            JOIN students_classes sc ON s.student_id = sc.student_id
            JOIN section_offerings so ON sc.offering_code = so.offering_code
            JOIN courses c ON so.course_code = c.course_code
            WHERE s.student_id = '$student_id'";

    $result = $conn->query($sql);
    ?>

</head>
    <body>
        
        <h1>Welcome to ITmosys</h1>

        <div class="container">
            <h2>View Student EAF</h2>
            <div class="separator"></div>
            <?php
                echo "<h3>Welcome!</h3>";
                echo "<h3>Student ID: $student_id</h3>";
            ?>

            <table>
                <tr>
                    <th>Course Title</th>
                    <th>Section</th>
                    <th>Class Days</th>
                    <th>Class Start Time</th>
                    <th>Class End Time</th>
                    <th>Professor</th>
                    <th>Room</th>
                </tr>
                <tr>
                    <?php
                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
                    ?>
                            <td><?php echo $row["course_title"];?></td>
                            <td><?php echo $row["section"];?></td>
                            <td><?php echo $row["class_days"];?></td>
                            <td><?php echo $row["class_start_time"];?></td>
                            <td><?php echo $row["class_end_time"];?></td>
                            <td><?php echo $row["professor"];?></td>
                            <td><?php echo $row["room"];?></td>
                </tr>
                <?php
                            }
                        }
                ?>
            </table>
        </div>
    </body>
</html>