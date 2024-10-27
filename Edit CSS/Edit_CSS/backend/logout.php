<?php
session_start(); // เริ่มเซสชัน
session_unset(); // ล้างข้อมูลเซสชันทั้งหมด
session_destroy(); // ทำลายเซสชัน
header("Location: index.php"); // เปลี่ยนหน้าไปยังหน้า login
exit();
?>
