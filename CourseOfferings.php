<html>
<head>
    <title>COURSE OFFERINGS</title>
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
            text-align: center;
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

        .enrollment-header {
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

    <!-- ITmosys Header -->
    <h1 class="itmosys-header">ITmosys</h1>

    <div class="container">
        <!-- Enrollment header in the top-left of the box -->
        <h2 class="enrollment-header">Course Offerings</h2>

        <!-- Line below the Enrollment header -->
        <div class="separator"></div>

        <div>
            <?php
            echo "<h3><font color='sky blue'>BLUE &emsp;&ensp;</font>Closed Section</h3>";
            echo "<h3><font color='green'>GREEN &emsp;</font>Open Section</h3>";
            ?>
        </div>

        <div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <label for="courseCode">Enter course code:</label>
            <input type='text' code='offeringCode' placeholder="ex: IT-PROG"> <br /><br />
            
            <?php

            ?>
            </form>
        </div>
    </div>

</body>
</html>
