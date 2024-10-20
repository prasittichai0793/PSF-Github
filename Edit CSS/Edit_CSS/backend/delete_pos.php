<?php
include '../class_conn.php';

if (isset($_GET['position_id'])) {
    $position_id = $_GET['position_id'];

    $db = new class_conn();
    $conn = $db->connect();

    // ลบข้อมูลพนักงานตาม position_id
    $sql = "DELETE FROM tb_position WHERE position_id = '$position_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('ลบข้อมูลพนักงานเรียบร้อยแล้ว'); window.location.href = 'show_position.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    $conn->close();
} else {
    echo "ไม่มีรหัสพนักงานที่ถูกเลือก";
}
?>
