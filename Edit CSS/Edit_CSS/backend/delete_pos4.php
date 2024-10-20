<?php
include '../class_conn.php';

if (isset($_GET['user_no'])) {
    $user_no = $_GET['user_no'];

    $db = new class_conn();
    $conn = $db->connect();

    // ลบข้อมูลพนักงานตาม user_no
    $sql = "DELETE FROM tb_user WHERE user_no = '$user_no'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('ลบข้อมูลพนักงานเรียบร้อยแล้ว'); window.location.href = 'show_position1.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    $conn->close();
} else {
    echo "ไม่มีรหัสพนักงานที่ถูกเลือก";
}
?>
