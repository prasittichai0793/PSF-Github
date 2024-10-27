<?php
session_start();
include 'class_conn.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// รับ IP Address ของผู้ใช้
$ip_address = $_SERVER['REMOTE_ADDR'];

// ตรวจสอบว่า IP เป็น IPv4 หรือไม่
if ($ip_address === "::1") {
    $ip_address = "127.0.0.1"; // กำหนดให้แสดง IPv4 สำหรับ localhost
} elseif (!filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
    $ip_address = "Not a valid IPv4 Address"; // ถ้าเป็น IPv6 หรือไม่ถูกต้อง
}

// แสดงผลในรูปแบบที่ต้องการ
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show IP Address</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>IPv4 Address: <?php echo htmlspecialchars($ip_address); ?></p>
</body>
</html>
