<html>
<head>
    <title>COURSE OFFERINGS</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .container {
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- ITmosys Header -->
    <h1 class="itmosys-header">ITmosys</h1>

    <div class="container">
        <!-- Title header in the top-left of the box -->
        <h2 class="title-header">Course Offerings</h2>

        <!-- Line below the Title header -->
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
