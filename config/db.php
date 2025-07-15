<?php
// Database configuration
$host = "localhost";      // সাধারণত localhost
$username = "root";       // XAMPP/WAMP এর ডিফল্ট ইউজারনেম
$password = "";           // ডিফল্ট পাসওয়ার্ড খালি থাকে
$database = "sardar_trust_bank";  // ডাটাবেজের নাম

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Charset সেট করে দাও যাতে বাংলা সহ সব কিছু সঠিক চলে
$conn->set_charset("utf8mb4");
?>
