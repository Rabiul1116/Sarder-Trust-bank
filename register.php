<?php
session_start();
require_once 'config/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // ফিল্ড খালি আছে কি না
    if (empty($username) || empty($email) || empty($password)) {
        $error = "সব ঘর পূরণ করুন।";
    } else {
        // ইমেইল আগেই আছে কি না
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "এই ইমেইল ইতোমধ্যে রেজিস্টার্ড।";
        } else {
            // পাসওয়ার্ড হ্যাশ করে সেভ
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $success = "রেজিস্ট্রেশন সফল! এখন লগইন করুন।";
                header("Location: login.php");
                exit();
            } else {
                $error = "রেজিস্ট্রেশন ব্যর্থ হয়েছে!";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Register - Sardar Trust Bank</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-box {
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            background-color: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1f8a4c;
        }
        .message {
            text-align: center;
            color: red;
            margin-bottom: 15px;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
        .login-link a {
            color: #27ae60;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Create Account</h2>

    <?php if ($error): ?>
        <div class="message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="message" style="color: green;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <input type="text" name="username" placeholder="নাম" required>
        <input type="email" name="email" placeholder="ইমেইল" required>
        <input type="password" name="password" placeholder="পাসওয়ার্ড" required>
        <button type="submit">Register</button>
    </form>

    <div class="login-link">
        ইতোমধ্যে অ্যাকাউন্ট আছে? <a href="login.php">লগইন করুন</a>
    </div>
</div>

</body>
</html>
