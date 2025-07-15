<?php
session_start();

// যদি অ্যাডমিন লগইন না থাকে
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// ডাটাবেজ কানেকশন
$conn = new mysqli("localhost", "root", "", "sardar_trust_bank");

// নাম্বার সাবমিট করলে ডাটাবেজে আপডেট
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bkash = $nagad = $rocket = '01780678838'; // সব নাম্বারে একটাই বসানো

    // চেক করে আপডেট নাকি ইনসার্ট করবে
    $check = $conn->query("SELECT * FROM payment_numbers");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE payment_numbers SET bkash='$bkash', nagad='$nagad', rocket='$rocket'");
    } else {
        $conn->query("INSERT INTO payment_numbers (bkash, nagad, rocket) VALUES ('$bkash', '$nagad', '$rocket')");
    }

    $message = "All numbers updated to 01780678838 successfully!";
}

// নাম্বারগুলো নিয়ে দেখাবে
$data = $conn->query("SELECT * FROM payment_numbers LIMIT 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Payment Numbers</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        label { display: block; margin-top: 15px; }
        input[type="text"] { width: 300px; padding: 5px; background: #f5f5f5; border: 1px solid #ccc; }
        input[type="submit"] { margin-top: 20px; padding: 8px 16px; background: #28a745; color: white; border: none; cursor: pointer; }
        .message { color: green; margin-top: 10px; }
    </style>
</head>
<body>

    <h2>📱 Set Bkash/Nagad/Rocket Numbers</h2>

    <?php if (isset($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Bkash Number:</label>
        <input type="text" name="bkash" value="<?= $data['bkash'] ?? '01780678838' ?>" readonly>

        <label>Nagad Number:</label>
        <input type="text" name="nagad" value="<?= $data['nagad'] ?? '01780678838' ?>" readonly>

        <label>Rocket Number:</label>
        <input type="text" name="rocket" value="<?= $data['rocket'] ?? '01780678838' ?>" readonly>

        <input type="submit" value="Confirm & Save">
    </form>

</body>
</html>
