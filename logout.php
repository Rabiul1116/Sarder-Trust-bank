<?php
session_start();

// সব সেশন ডেটা ধ্বংস করা
session_unset();
session_destroy();

// লগইন পেইজে রিডাইরেক্ট
header("Location: login.php");
exit();
