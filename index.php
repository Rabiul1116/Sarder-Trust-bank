<?php
session_start();

// যদি ইউজার লগইন থাকে, ড্যাশবোর্ডে রিডাইরেক্ট কর
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sardar Trust Bank</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            text-align: center;
            padding: 50px;
        }
        .logo {
            width: 150px;
            margin-bottom: 20px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        p {
            font-size: 18px;
            color: #34495e;
        }
        .btn {
            display: inline-block;
            margin: 20px 10px 0;
            padding: 12px 30px;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #219150;
        }
    </style>
</head>
<body>

    <!-- Logo এখানে রাখবে -->
    <img src="assets/logo.png" alt="Sardar Trust Bank Logo" class="logo" />

    <h1>Welcome to Sardar Trust Bank</h1>
    <p>Your trusted partner in banking.</p>

    <a href="login.php" class="btn">Login</a>
    <a href="register.php" class="btn">Register</a>

</body>
</html>
