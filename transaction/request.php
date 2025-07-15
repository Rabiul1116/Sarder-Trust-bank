<?php
session_start();
require_once '../config/db.php';

// ইউজার লগইন চেক (session এর মাধ্যমে)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// ফর্ম সাবমিট হলে
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type']; // deposit / withdraw
    $amount = floatval($_POST['amount']);

    if ($amount <= 0) {
        $message = "Please enter a valid amount.";
    } else {
        // ট্রানজেকশন রিকোয়েস্ট ইনসার্ট কর
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("isd", $user_id, $type, $amount);
        if ($stmt->execute()) {
            $message = "Transaction request sent successfully. Waiting for admin approval.";
        } else {
            $message = "Error: Could not submit your request.";
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction Request Form</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 15px; }
        select, input[type="number"] { width: 100%; padding: 8px; margin-top: 5px; }
        input[type="submit"] { margin-top: 20px; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        .message { margin-top: 15px; color: green; }
        .error { color: red; }
    </style>
</head>
<body>

    <h2>Transaction Request Form</h2>

    <?php if ($message): ?>
        <p class="<?= strpos($message, 'Error') === 0 ? 'error' : 'message' ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="type">Transaction Type:</label>
        <select id="type" name="type" required>
            <option value="">-- Select --</option>
            <option value="deposit">Deposit</option>
            <option value="withdraw">Withdraw</option>
        </select>

        <label for="amount">Amount (৳):</label>
        <input type="number" id="amount" name="amount" min="1" step="0.01" required>

        <input type="submit" value="Send Request">
    </form>

</body>
</html>
