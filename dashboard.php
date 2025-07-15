<?php
session_start();
require_once 'config/db.php';

// ইউজার লগইন চেক করা
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ইউজারের তথ্য নেয়া
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// ব্যালেন্স হিসাব (ধরা যাক users টেবিলে balance আছে)
$stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$balance = 0;
if ($row = $result->fetch_assoc()) {
    $balance = $row['balance'];
}
$stmt->close();

// ট্রানজেকশন ইতিহাস নেয়া
$stmt = $conn->prepare("SELECT type, amount, status, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$transactions = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { color: #333; }
        .balance { font-size: 1.5em; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f4f4f4; }
        .status-pending { color: orange; }
        .status-approved { color: green; }
        .status-rejected { color: red; }
        .logout { float: right; }
    </style>
</head>
<body>

    <a href="logout.php" class="logout">Logout</a>
    <h2>Welcome, <?= htmlspecialchars($user['username']) ?></h2>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>

    <div class="balance">
        <strong>Current Balance:</strong> ৳<?= number_format($balance, 2) ?>
    </div>

    <h3>Recent Transactions</h3>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Amount (৳)</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($transactions->num_rows > 0): ?>
                <?php while ($tx = $transactions->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars(ucfirst($tx['type'])) ?></td>
                        <td><?= number_format($tx['amount'], 2) ?></td>
                        <td class="status-<?= strtolower($tx['status']) ?>"><?= htmlspecialchars(ucfirst($tx['status'])) ?></td>
                        <td><?= htmlspecialchars($tx['created_at']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No transactions found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
