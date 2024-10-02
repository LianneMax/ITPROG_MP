<html>
<head>
    <title>ENROLLMENT</title>
    <style>
        /* Import Montserrat font */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        body {
            font-family: 'Montserrat', New Times Romans;
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
            top: 50px;
            font-size: 36px;
            font-family: fantasy;
        }

        .container {
            position: relative;
            text-align: left;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            margin: 0;
            padding-bottom: 20px;
            color: #333;
            font-family: 'Montserrat', New Times Romans;
        }

        .enrollment-header {
            position: absolute;
            top: 20px;
            left: 20px;
            font-family: 'Montserrat', New Times Romans;
            color: #333;
            font-size: 24px;
        }

        button {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            font-size: 16px;
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

    <h1 class="itmosys-header">ITmosys</h1>

    <div class="container">
        <h2 class="enrollment-header">ENROLLMENT</h2>

        <button onclick="window.location.href='add_class.php'">Add Classes</button>
        <button onclick="window.location.href='drop_class.php'">Remove Classes</button>
    </div>

</body>
</html>