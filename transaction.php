<?php
session_start();
require_once 'config/db.php';

// ইউজার লগইন চেক
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type']; // deposit or withdraw
    $amount = floatval($_POST['amount']);

    if ($amount <= 0) {
        $error = "সঠিক পরিমাণ লিখুন।";
    } else {
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("isd", $user_id, $type, $amount);
        if ($stmt->execute()) {
            $success = "আপনার {$type} অনুরোধটি পাঠানো হয়েছে। Admin অনুমোদনের অপেক্ষায়।";
        } else {
            $error = "অনুরোধ ব্যর্থ হয়েছে, আবার চেষ্টা করুন।";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction Request</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f2f2; padding: 50px; text-align: center; }
        .box {
            background: white;
            padding: 30px;
            width: 400px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        select, input[type="number"], button {
            padding: 12px;
            width: 100%;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #27ae60;
            color: white;
            font-weight: bold;
        }
        button:hover {
            background-color: #219150;
        }
        .msg {
            margin-top: 10px;
            font-weight: bold;
        }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<div class="box">
    <h2>💳 Transaction Request</h2>

    <?php if ($error): ?>
        <div class="msg error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="msg success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Type:</label>
        <select name="type" required>
            <option value="deposit">Deposit</option>
            <option value="withdraw">Withdraw</option>
        </select>

        <label>Amount (৳):</label>
        <input type="number" name="amount" min="1" step="0.01" required />

        <button type="submit">Send Request</button>
    </form>

    <p><a href="dashboard.php">⬅️ Back to Dashboard</a></p>
</div>

</body>
</html>
