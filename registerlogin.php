<?php
// Database connection
$host = 'localhost';
$db = 'ywca';
$user = 'root'; // Adjust based on your database setup
$pass = ''; // Adjust based on your database setup

// Create connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registration Process
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    if (isset($_POST['registerName']) && isset($_POST['registerEmail']) && isset($_POST['registerPassword'])) {
        $name = $_POST['registerName'];
        $email = $_POST['registerEmail'];
        $password = $_POST['registerPassword'];

        // Check if the email already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "<script>alert('This email is already registered.');</script>";
        } else {
            // Hash the password and store in the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $name, $email, $hashedPassword);
            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! You can now log in.');</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        /* Basic reset and body styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-image: url('img/loginimg.jpg');
            color: #333;
        }

        /* Container for the registration form */
        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Form group styling */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color:rgb(6, 70, 122);
        }

        /* Button styling */
        button {
            width: 100%;
            padding: 12px;
            background-color:rgb(17, 117, 175);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color:rgb(14, 150, 160);
        }

        /* Link styling */
        .links {
            text-align: center;
            margin-top: 15px;
        }

        .links a {
            color:rgb(5, 106, 131);
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>

    <script>
        // Form validation
        function validateForm() {
            var name = document.forms["registrationForm"]["registerName"].value;
            var email = document.forms["registrationForm"]["registerEmail"].value;
            var password = document.forms["registrationForm"]["registerPassword"].value;

            if (name == "" || email == "" || password == "") {
                alert("All fields must be filled out.");
                return false;
            }

            // Simple email validation
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Registration</h2>
    <form name="registrationForm" method="POST" action="" onsubmit="return validateForm()">
        <div class="form-group">
            <input type="text" name="registerName" placeholder="Full Name" required>
        </div>
        <div class="form-group">
            <input type="email" name="registerEmail" placeholder="Email" required>
        </div>
        <div class="form-group">
            <input type="password" name="registerPassword" placeholder="Password" required>
        </div>
        <button type="submit" name="register">Register</button>
    </form>

    <div class="links">
        <p>Already have an account? <a href="userlogin.php">Login here</a></p>
    </div>
</div>

</body>
</html>
