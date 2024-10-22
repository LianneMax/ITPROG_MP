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
            width: 350px;
            height: 210px;
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
            font-family: "Times New Roman", serif; /* Changed button font */
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

    //Create connection
    $conn = new mysqli($server, $username, $password);

    $sql = "SELECT student_id, student_name FROM students";
    $result = $conn->query($sql);

    if (isset($_SESSION['student_id'])) {
        $student_id = $_SESSION['student_id'];
    }
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
        </div>
    </body>
</html>