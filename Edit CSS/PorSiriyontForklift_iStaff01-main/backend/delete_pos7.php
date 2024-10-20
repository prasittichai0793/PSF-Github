<?php
include '../class_conn.php';

if (isset($_GET['admin_no'])) {
    $admin_no = $_GET['admin_no'];

    $db = new class_conn();
    $conn = $db->connect();

    // ลบข้อมูลพนักงานตาม admin_no
    $sql = "DELETE FROM tb_admin WHERE admin_no = '$admin_no'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('ลบข้อมูลพนักงานเรียบร้อยแล้ว'); window.location.href = 'show_position7.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    $conn->close();
} else {
    echo "ไม่มีรหัสพนักงานที่ถูกเลือก";
}
?>
