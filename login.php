<?php
// Hardcoded username and password for login
$correct_username = 'YWCA';    // Username to be matched
$correct_password = 'YWCA@123'; // Password to be matched

// Initialize error message variable
$error_message = "";

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the username and password match the hardcoded values
    if ($username === $correct_username && $password === $correct_password) {
        // Login successful: set session variables
        session_start();
        $_SESSION['user_name'] = $username;
        
        // Redirect to another page (e.g., membership form)
        header("Location: form.html");
        exit();
    } else {
        // Invalid credentials
        $error_message = "Invalid credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - YWCA Membership</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: url('img/loginimg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            background: rgba(255, 255, 255, 0.5); /* Transparency */
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .login-container h2 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
            color: #0077b6;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            background: #0077b6;
            border: none;
            font-size: 1rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: #005f96;
        }
        .error {
            text-align: center;
            color: #e63946;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #6c757d;
        }
        .footer-text a {
            text-decoration: none;
            color: #0077b6;
        }
        .footer-text a:hover {
            text-decoration: underline;
        }
        .login-container .form-control {
            border-radius: 6px;
            box-shadow: none;
            border: 1px solid #ddd;
            transition: border-color 0.3s;
        }
        .login-container .form-control:focus {
            border-color: #0077b6;
            box-shadow: 0 0 8px rgba(0, 119, 182, 0.3);
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Login to Membership</h2>
    <?php if ($error_message) { ?>
        <div class="error">
            <?php echo $error_message; ?>
        </div>
    <?php } ?>
    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" required placeholder="Enter your username">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <!-- Button to Redirect to Registration Page -->
    </form><br>
    <a href="registerlogin.php">
    <button type="button" class="btn btn-primary">If you are a user, click here to register</button>
     </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
