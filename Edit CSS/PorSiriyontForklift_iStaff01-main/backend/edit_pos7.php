<?php
include '../class_conn.php';

// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (!isset($_GET['id'])) {
    die("กรุณาระบุ ID ของผู้ดูแล");
}

$admin_id = $_GET['id'];

$db = new class_conn();
$conn = $db->connect();

// ดึงข้อมูลผู้ดูแลจาก ID
$sql = "SELECT * FROM tb_admin WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("ไม่พบข้อมูลผู้ดูแล");
}

$admin = $result->fetch_assoc();

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = $_POST['admin_name'];
    $admin_username = $_POST['admin_username'];
    $admin_password = $_POST['admin_password'];
    $position_id = $_POST['position_id'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $update_sql = "UPDATE tb_admin SET 
                   admin_name = ?, 
                   admin_username = ?, 
                   admin_password = ?, 
                   position_id = ? 
                   WHERE admin_id = ?";
    
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi",  $admin_name, $admin_username, $admin_password, $position_id, $admin_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Successful - แก้ไขสำเร็จ'); window.location.href = 'show_position7.php';</script>";
        exit(); // หยุดการทำงานหลังจาก redirect
    } else {
        echo "<div style='color: red; text-align: center;'>เกิดข้อผิดพลาด: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ดูแล</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .main-content {
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 97%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group select {
            width: 100%;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            color: #fff;
            background: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-save {
            background: #28a745;
        }
        .btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>แก้ไขข้อมูลผู้ดูแล</h1>
                <form action="edit_pos7.php?id=<?php echo $admin_id; ?>" method="post">
                    <div class="form-group">
                        <label for="admin_name">ชื่อ:</label>
                        <input type="text" id="admin_name" name="admin_name" value="<?php echo htmlspecialchars($admin['admin_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="admin_username">ชื่อผู้ใช้:</label>
                        <input type="text" id="admin_username" name="admin_username" value="<?php echo htmlspecialchars($admin['admin_username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="admin_password">รหัสผ่าน:</label>
                        <input type="password" id="admin_password" name="admin_password" value="<?php echo htmlspecialchars($admin['admin_password']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="position_id">ตำแหน่ง:</label>
                        <input type="text" id="position_id" name="position_id" value="<?php echo htmlspecialchars($admin['position_id']); ?>" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> บันทึก</button>
                    </div>
                </form>
            </header>
        </div>
    </div>
</body>
</html>
