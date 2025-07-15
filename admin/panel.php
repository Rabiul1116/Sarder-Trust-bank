<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "sardar_trust_bank");

// Approve or Reject transaction
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == "approve") {
        $conn->query("UPDATE transactions SET status='approved' WHERE id=$id");
    } elseif ($action == "reject") {
        $conn->query("UPDATE transactions SET status='rejected' WHERE id=$id");
    }
}

// Get all pending transactions
$result = $conn->query("SELECT * FROM transactions WHERE status='pending'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Approve/Reject Transactions</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        a { padding: 5px 10px; background: green; color: white; text-decoration: none; margin-right: 5px; border-radius: 3px; }
        a.reject { background: red; }
    </style>
</head>
<body>

    <h2>🔐 Admin Panel</h2>
    <p>Below are the pending transaction requests:</p>

    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['user_id'] ?></td>
            <td><?= $row['type'] ?></td>
            <td><?= $row['amount'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <a href="?action=approve&id=<?= $row['id'] ?>">Approve</a>
                <a href="?action=reject&id=<?= $row['id'] ?>" class="reject">Reject</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
