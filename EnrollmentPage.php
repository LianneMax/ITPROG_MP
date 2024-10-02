<html>
<head>
    <title>ENROLLMENT</title>
    <style>
    
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        body {
            font-family: fantasy, 'Montserrat', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .container {
            text-align: center;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
            font-family: fantasy, 'Montserrat', sans-serif;
        }
        h1 {
            margin-bottom: 20px;
        }
        h2 {
            margin-bottom: 30px;
        }
        button {
            padding: 15px 30px;
            margin: 10px;
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
    <div class="container">
        <h1>ITmosys</h1> 
        <h2>ENROLLMENT</h2>
        <button onclick="window.location.href='add_class.php'">Add Classes</button>
        <button onclick="window.location.href='drop_class.php'">Remove Classes</button>
    </div>
</body>
</html>