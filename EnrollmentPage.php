<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENROLLMENT</title>
    <style>
        /* Import Montserrat font */
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
            top: 10px; /* Reduce the top spacing to make ITmosys closer */
            font-size: 36px;
            font-family: fantasy;
            color: #5b8ba4; /* Same color as buttons */
        }

        .container {
            position: relative;
            text-align: left;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin-top: 40px; /* Reduced margin-top for closer distance */
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
            margin-top: 40px; /* Add space above the line */
            margin-bottom: 20px; /* Add space below the line */
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
    </style>
</head>
<body>

    <!-- ITmosys Header -->
    <h1 class="itmosys-header">ITmosys</h1>

    <div class="container">
        <!-- Enrollment header in the top-left of the box -->
        <h2 class="enrollment-header">ENROLLMENT</h2>

        <!-- Line below the Enrollment header -->
        <div class="separator"></div>

        <!-- Buttons stacked on top of each other -->
        <button onclick="window.location.href='add_class.php'">Add Classes</button>
        <button onclick="window.location.href='drop_class.php'">Remove Classes</button>
    </div>

</body>
</html>
