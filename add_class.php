<html>
<head>
    <title>Add Classes</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        body {
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

        .itmosys-header {
            position: absolute;
            top: 10px; 
            font-size: 36px;
            font-family: fantasy;
            color: #5b8ba4; 
        }

        .container {
            position: relative;
            text-align: left;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 1000px;
            height: 500px;
            margin-top: 40px;
        }

        h2 {
            margin: 0;
            padding-bottom: 10px;
            color: #333;
            font-family: 'Montserrat', Times New Roman, serif;
        }

        .add-class-header {
            position: absolute;
            top: 20px;
            left: 20px;
            font-family: 'Montserrat', Times New Roman, serif;
            color: #333;
            font-size: 24px;
        }

        /* Line separator placed directly below Enrollment */
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
    </style>
</head>
<body>
<?php
    //After turning on SQL on XAMPP, you can manage the DB via this url:
    // http://localhost/phpmyadmin
    $server = "localhost";
    $username = "root"; //will change this later
    $password = "";

    // Initiate connection to DB
    $connection = mysqli_connect($server, $username, $password);

    if(!$connection)
        die("could not connect".mysqli_connect_error());
    else echo "CONNECTION TO DB SUCCESSFUL";

    $dbname = "itmosys_db";
    // mysqli_select_db($connection, $dbname);

    // $query = "SELECT * FROM $dbname";
    // $statement = mysqli_query($connection, $query);

    //try to insert ITCMSY1
    $query = "INSERT INTO $dbname.course_codes (course_code) VALUES ('ITCMSY1')";
    $statement = mysqli_query($connection, $query);
    ?>    
    
    <!-- ITmosys Header -->
    <h1 class="itmosys-header">ITmosys</h1>

    <div class="container">
        <!-- Enrollment header in the top-left of the box -->
        <h2 class="add-class-header">Add/Drop Classes Facility</h2>

        <!-- Line below the Enrollment header -->
        <div class="separator"></div>

    </div>



</body>
</html>
