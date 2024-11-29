<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="login_body">

    <h1 class="login_title">Welcome to ITmosys</h1>

    <div class="login_container">
        <h2 class="login_subtitle">Admin Login</h2>

        <div class="login_separator"></div>

        <div class="login_formDiv">
            <?php
            session_start(); // Starts the session

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
                $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
                $pass = mysqli_real_escape_string($conn, $_POST['password']);

                // Store the admin ID in a session variable
                $_SESSION['admin_id'] = $admin_id;

                // Query to check if admin exists
                $sql = "SELECT * FROM admins WHERE admin_id = '$admin_id' AND password = '$pass'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Success: Redirect to admin dashboard or relevant page
                    echo "<p>Login successful! Redirecting...</p>";
                    header("refresh:2;url=admin_menu.php");
                } else {
                    // Failure: Show error message
                    echo "<p style='color:red;'>Invalid admin ID or password.</p>";
                }

                // Close connection
                $conn->close();
            }
            ?>

            <form method="post" action="" class="login_form">
                <label for="admin_id" class="login_label">Admin ID:</label>
                <input type="text" id="admin_id" name="admin_id" class="login_input" required>
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
