<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        .container { 
            position: relative; 
            text-align: left; 
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 350px;
            height: 310px;
            margin-top: 40px;
        }

        body {
            background-image: url('for loop justinn.png');
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

        button {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            font-size: 16px;
            font-family: "Times New Roman", serif;
            border: none;
            border-radius: 5px;
            background-color: #5b8ba4;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #497285;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: "Times New Roman", serif;
            font-size: 16px;
        }

        label {
            font-family: 'Montserrat', Times New Roman, serif;
            margin-top: 10px;
            display: block;
        }

    </style>

</head>
<body>

    <h1>Welcome to ITmosys</h1>

    <div class="container">
        <h2>LOGIN ka na SIS</h2>

        <div class="separator"></div>

        <div class="formDiv">
            <?php
            session_start(); //starts the session

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Database connection
                $servername = "localhost";
                $username = "root"; // Change if necessary
                $password = ""; // Change if necessary
                $dbname = "itmosys_db";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetching form data
                $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
                $pass = mysqli_real_escape_string($conn, $_POST['password']);

                // Store the student number in a session variable
                $_SESSION['student_id'] = $student_id;

                // Query to check if student exists
                $sql = "SELECT * FROM students WHERE student_id = '$student_id' AND password = '$pass'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Success: Redirect to enrollment page or dashboard
                    echo "<p>Login successful! Redirecting...</p>";
                    header("refresh:2;url=EnrollmentPage.php");
                } else {
                    // Failure: Show error message
                    echo "<p style='color:red;'>Invalid student ID or password.</p>";
                }

                // Close connection
                $conn->close();
            }
            ?>

            <form method="post" action="">
                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id" required>
                <br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <br><br>
                <button type="submit" class="signin">Sign In</button>
            </form>
        </div>
    </div>

</body>
</html>