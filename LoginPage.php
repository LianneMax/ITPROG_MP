<html>
<head><title>Login Page</title>

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
            background-image: url('for loop justinn.png');
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

        input[type="text"],
        input[type="password"] {
            width: 100%; /* Makes the input fields take the full width of the container */
            padding: 10px; /* Adds padding inside the input fields */
            margin-top: 5px;
            border: 1px solid #ccc; /* Adds a border to the input fields */
            border-radius: 5px; /* Rounds the corners of the input fields */
            box-sizing: border-box; /* Ensures padding and border are included in the width */
            font-family: "Times New Roman", serif;
            font-size: 16px;
        }

        label {
            font-family: 'Montserrat', Times New Roman, serif;
            margin-top: 10px;
            display: block; /* Makes sure the label takes up the entire width */
        }

    </style>

</head>
    <body>
        
        <h1>Welcome to ITmosys</h1>

        <div class="container">
            <h2>LOGIN ka na SIS</h2>
    
            <div class="separator"></div>
            
            <div class="formDiv">
                <form method="post" action="EnrollmentPage.php">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <br><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <br><br>
                    <button type="submit" class="signin">Sign In</button>
                </form>
            </div>
        </div>
    </body>
</html>