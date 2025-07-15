<?php
session_start();
require_once 'config/db.php';

// যদি ইউজার আগেই লগইন থাকে, ড্যাশবোর্ডে রিডাইরেক্ট কর
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "সব ফিল্ড পূরণ করুন।";
    } else {
        // ইউজার ডেটাবেস থেকে নিয়ে আসা
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // পাসওয়ার্ড চেক করা
            if (password_verify($password, $user['password'])) {
                // লগইন সফল, সেশন সেট করো
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "পাসওয়ার্ড ভুল।";
            }
        } else {
            $error = "ইমেইল পাওয়া যায়নি।";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Sardar Trust Bank</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f9fb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 320px;
        }
        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
            text-align: center;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
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
            background-color: #219150;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
        .register-link {
            margin-top: 15px;
            text-align: center;
        }
        .register-link a {
            color: #27ae60;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="email" name="email" placeholder="ইমেইল" required />
        <input type="password" name="password" placeholder="পাসওয়ার্ড" required />
        <button type="submit">Login</button>
    </form>

    <div class="register-link">
        নতুন ইউজার? <a href="register.php">রেজিস্টার করুন</a>
    </div>
</div>

</body>
</html>
