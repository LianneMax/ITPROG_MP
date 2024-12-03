<html>
<head>
    <title>Login Page</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body style="background-image: url('../pages/Client.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

        <i class="fas fa-tools maintenance_icon" onclick="window.location.href='AdminLoginPage.php'" title="Maintenance" 
           style="font-size: 40px; position: absolute; top: 20px; right: 20px; color: #305a6d; cursor: pointer;"></i>


    <div class="login_container">
        <h2 class="login_subtitle">LOGIN ka na SIS</h2>

        <div class="login_separator"></div>

        <div class="login_formDiv">
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
                    header("refresh:2;url=EnrollmentMenu.php");
                } else {
                    // Failure: Show error message
                    echo "<p style='color:red;'>Invalid student ID or password.</p>";
                }

                // Close connection
                $conn->close();
            }
            ?>

            <form method="post" action="" class="login_form">
                <label for="student_id" class="login_label">Student ID:</label>
                <input type="text" id="student_id" name="student_id" class="login_input" required>
                <br><br>
                <label for="password" class="login_label">Password:</label>
                <input type="password" id="password" name="password" class="login_input" required>
                <br><br>
                <button type="submit" class="login_button">Sign In</button>
            </form>
        </div>
    </div>

</body>
</html>
