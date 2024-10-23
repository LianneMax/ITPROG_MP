<html>
<head>
    <title>Add Classes</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- ITmosys Header -->
    <h1 class="itmosys-header">ITmosys</h1>

    <div class="container">
        <!-- Enrollment header in the top-left of the box -->
        <h2 class="title-header">Add/Drop Classes Facility</h2>

        <!-- Line below the Enrollment header -->
        <div class="separator"></div>

        <div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <label for="offeringCode">Class code to add:</label>
            <input type='text' code='offeringCode' placeholder="ex: 1234"> <br /><br />
            
            <?php
            // error_reporting(E_ERROR | E_PARSE);
            // echo $_GET['offeringCode'];
            ?>
            </form>
        </div>

    </div>

    

    <?php
        //put this in 1 sql config file 

        //After turning on SQL on XAMPP, you can manage the DB via this url:
        // http://localhost/phpmyadmin
        $server = "localhost";
        $username = "root"; //will change this later
        $password = "";

        // Initiate connection to DB
        $connection = mysqli_connect($server, $username, $password);

        if(!$connection)
            die("could not connect".mysqli_connect_error());
        // else echo "CONNECTION TO DB SUCCESSFUL";

        $dbname = "itmosys_db";
        // mysqli_select_db($connection, $dbname);

        // $query = "SELECT * FROM $dbname";
        // $statement = mysqli_query($connection, $query);

        //try to insert ITCMSY1
        // $query = "INSERT INTO $dbname.course_codes (course_code) VALUES ('ITCMSY1')";
        // $statement = mysqli_query($connection, $query);
        ?>    
        




</body>
</html>
