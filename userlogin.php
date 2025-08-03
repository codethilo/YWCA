<?php
// Database connection
$host = 'localhost';
$db = 'ywca';
$user = 'root'; 
$pass = ''; 

// Create connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Login Process
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    if (isset($_POST['loginEmail']) && isset($_POST['loginPassword'])) {
        $email = $_POST['loginEmail'];
        $password = $_POST['loginPassword'];

        // Check if the email exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                echo "<script>alert('Login successful! Welcome, " . $user['name'] . "'); window.location.href = 'dashboard.php';</script>";
            } else {
                echo "<script>alert('Invalid password.');</script>";
            }
        } else {
            echo "<script>alert('No user found with this email.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        /* Container for the login form */
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
            border-color:rgb(34, 21, 156);
        }

        /* Button styling */
        button {
            width: 100%;
            padding: 12px;
            background-color:rgb(62, 19, 218);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color:rgb(92, 84, 122);
        }

        /* Link styling */
        .links {
            text-align: center;
            margin-top: 15px;
        }

        .links a {
            color:rgb(70, 40, 206);
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        /* Error message styling */
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

    </style>

    <script>
        // Form validation
        function validateForm() {
            var email = document.forms["loginForm"]["loginEmail"].value;
            var password = document.forms["loginForm"]["loginPassword"].value;

            if (email == "" || password == "") {
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
    <h2>Login</h2>
    <form name="loginForm" method="POST" action="" onsubmit="return validateForm()">
        <div class="form-group">
            <input type="email" name="loginEmail" placeholder="Email" required>
        </div>
        <div class="form-group">
            <input type="password" name="loginPassword" placeholder="Password" required>
        </div>
        <button type="submit" name="login">Login</button>
    </form>

    <div class="links">
        <p>Forgot your password? <a href="forgot_password.php">Click here</a> to reset it.</p>
        <p>Don't have an account? <a href="registerlogin.php">Register here</a></p>
    </div>
</div>

</body>
</html>
