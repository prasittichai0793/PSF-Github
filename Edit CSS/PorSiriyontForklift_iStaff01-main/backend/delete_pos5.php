<?php
include '../class_conn.php';

if (isset($_GET['hr_id'])) {
    $hr_no = $_GET['hr_id'];

    $db = new class_conn();
    $conn = $db->connect();

    // ลบข้อมูลพนักงานตาม hr_no
    $sql = "DELETE FROM tb_hr WHERE hr_id = '$hr_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('ลบข้อมูลพนักงานเรียบร้อยแล้ว'); window.location.href = 'show_position5.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    $conn->close();
} else {
    echo "ไม่มีรหัสพนักงานที่ถูกเลือก";
}
?>
