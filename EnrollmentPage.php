<html>
    <title>Enrollment Page</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        form {
            text-align: center;
            margin: 20px 0;
        }
        label {
            font-weight: bold;
        }
        input {
            padding: 5px;
            margin: 10px;
            width: 200px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .enrolled-classes {
            margin: 20px 0;
        }
        .enrolled-classes ul {
            list-style: none;
            padding: 0;
        }
        .enrolled-classes li {
            background-color: #eee;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enrollment Page</h2>

        <form method="POST" action="">
            <label for="classNbr">Enter Class Number:</label>
            <input type="text" id="classNbr" name="classNbr" required>
            <button type="submit" name="add">Add Class</button>
            <button type="submit" name="drop">Drop Class</button>
        </form>

        <h3>Enrolled Classes</h3>
        <div class="enrolled-classes">
        </div>
    </div>
</body>
</html>
