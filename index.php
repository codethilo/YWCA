<?php
// Start session to check for login status
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YWCA Membership Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background: url('img/membershipbg.jpg') no-repeat center center fixed;
            background-size: cover;
            color:linear-gradient(90deg,rgb(24, 34, 46), #0056b3);;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        /* Header Styles */
        header {
            background:rgb(67, 82, 126);
            color: white;
            padding: 1rem 0;
            text-align: center;
            animation: fadeInDown 1.5s ease-in-out;
        }

        .nav-link {
            font-weight: bold;
            color: white !important;
            text-decoration: none;
            transition: text-decoration 0.3s ease;
        }

        .nav-link:hover {
            text-decoration: underline;
        }

        /* Main Section Styles */
        main {
            background: rgba(255, 255, 255, 0.5); /* Transparency */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin: 30px auto;
            max-width: 1200px;
            animation: fadeInUp 1.5s ease-in-out;
        }

        .section {
            margin-bottom: 40px;
            padding: 20px;
            background: rgba(250, 247, 247, 0.5); /* Transparency */
            border-radius: 8px;
            animation: slideIn 1.2s ease-in-out;
        }

        .section h2 {
            margin-bottom: 20px;
            color: #0056b3;
        }

        /* Button Styles */
        .btn-primary {
            background:rgb(29, 66, 105);
            border-color:rgb(10, 23, 37);
            color: white;
            font-weight: bold;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            animation: popIn 1.5s ease-in-out;
        }

        .btn-primary:hover {
            background:rgb(139, 180, 224);
            transform: scale(1.1);
        }

        /* Footer Styles */
        footer {
            background: linear-gradient(90deg,rgb(24, 34, 46), #0056b3);
            color: white;
            text-align: center;
            padding: 1.5rem 0;
            animation: fadeIn 2s ease-in-out;
            margin-top: 30px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                background-size: cover;
            }

            main {
                padding: 20px;
                margin: 15px auto;
            }

            .btn-primary {
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="animate__animated animate__fadeInDown">
        <h1>YWCA Membership Portal</h1>
        <nav>
            <ul class="nav justify-content-center mt-3">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="form.php">Membership Form</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <!-- Welcome Section -->
        <section class="text-center animate__animated animate__fadeInUp">
            <h2 class="text-primary">YWCA of Madras</h2>
            <p>Join us in empowering women and creating a positive impact in society.</p>
        </section>

        <!-- Dynamic Content Section -->
        <section id="dynamic-content" class="text-center">
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                echo "<h3>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</h3>";
                echo "<p>You are logged in to the YWCA Membership Portal.</p>";
            } else {
                echo "<h3>Become a Member</h3>";
                echo "<p>Join us today to empower women and create positive change in the world. <a href='login.php' class='btn btn-primary'>Login Now</a></p>";
            }
            ?>
        </section>

        <!-- YWCA Overview Section -->
        <section class="section">
            <h2>About YWCA</h2>
            <p>
                Since its inception in the late 10th century, the Young Women's Christian Association (YWCA) has grown into a global network of great repute. Today, it is the largest membership-based women's movement in the world, empowering women to demand justice and struggle against social, cultural, economic, and political injustice.
            </p>
            <p>
                Rooted in Christian faith and heritage, the YWCA of Madras was founded in 1892. For over 125 years, this community of women has been working tirelessly for the empowerment of women and children, irrespective of caste, class, or creed. The motto of the YWCA is "By Love Serve One Another."
            </p>
            <p>
                The YWCA of Madras is affiliated with the YWCA of India, which is part of the World YWCA network spanning over 120 countries. Our 12-acre campus in the heart of the city is a haven for environmental conservation, featuring lush greenery, a pond with fish, turtles, ducks, and geese, and a peaceful atmosphere.
            </p>
        </section>

        
        <!-- Campus Projects Section -->
        <section class="section">
            <h2>Projects on Campus</h2>
            <ul>
                <li>The Lily Pithavadian Balwadi for children</li>
                <li>The Pastor Peiffer Home for adolescents</li>
                <li>The Sahodan project and the Navajeevan project for women</li>
                <li>Adopt a Granny and St. Margaret's Place for senior citizens</li>
                <li>Community projects including Urban Community Development, Community College, and Rural Development</li>
                <li>The International Guest House</li>
                <li>Hostel for women</li>
                <li>Public Affairs and Social Issues program</li>
            </ul>
        </section>

        <!-- Membership Instructions Section -->
        <section class="section">
            <h2>Membership Instructions</h2>
            <p>
                Each person will be given only one form to be filled out personally at the YWCA of Madras. Women without recommendations will be interviewed by the General Secretary, Membership Coordinator, or a Membership Committee Member.
            </p>
            <p>
                Women with continuous membership in another YWCA and wishing to seek membership in the YWCA of Madras must bring proof of their previous membership.
            </p>
            <p>
                Participation in the orientation is essential for all new members. Two recent photographs must be submitted along with the membership fee after orientation.
            </p>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2025 YWCA of Madras | Empowering Women, Building Communities</p>
        <p>Contact: The Executive General Secretary | Phone: 25324251 / 25324261</p>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

