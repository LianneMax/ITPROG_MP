<?php
// PHASE 1  - Client Side
// LOGIN PAGE               ⇒ Justin
// COURSE OFFERINGS PAGE	⇒ Jer
// ENROLLMENT PAGE          ⇒ Max, Jer
// - Functionalities:
// - Add/drop class
// - Checkout and confirm
// VIEW EAF                 ⇒ Charles
?>

<html>
<head>
    <title>Enrollment Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        h1 {
            margin-bottom: 30px;
            color: #333;
        }
        button {
            padding: 15px 30px;
            margin: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enrollment Page</h1>
        <button onclick="window.location.href='add_class.php'">Add Classes</button>
        <button onclick="window.location.href='drop_class.php'">Remove Classes</button>

        <?php echo "PHP is working!"; ?>

    </div>
</body>
</html>
