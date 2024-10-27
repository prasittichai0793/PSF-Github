<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G เพิ่มข้อมูลพนักงานAdmin</title>

    <link rel="icon" href="../images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <?php include '../class_conn.php'; ?>
    <?php include 'calender.php'; ?>

    <style>
        body {
            margin: 0;
            font-family: Verdana, sans-serif;
        }

        .display-container {
            width: 100%;
            height: 100vh;
        }

        .container-content {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            width: 60%;
            max-width: 700px;
        }

        .content span {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content label {
            font-size: 15px;
        }

        .content input,
        .content select {
            font-size: 14px;
            width: 100%;
            height: auto;
            box-sizing: border-box;
        }

        .content button {
            font-size: 14px;
            color: #fff;
            padding: 10px 15px;
            margin-top: 10px;
            border: none;
        }

        .btn-save {
            background: #259b24;
        }

        .btn-save:hover {
            background: #056f00;
            color: #fff;
        }

        .btn-cancel {
            background: #e51c23;
        }

        .btn-cancel:hover {
            background: #b0120a;
            color: #fff;
        }

        .fa-save,
        .fa-times {
            margin-right: 5px;
        }

        .hr_exp {
            pointer-events: none;
        }

        @media screen and (max-width: 930px) {
            .content {
                min-width: 150px;
            }

            .content button {
                font-size: 12px;
                color: #fff;
                padding: 5px 10px;
                margin-top: 10px;
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="display-container">
        <div class="display-content">
            <div class="container-content">
                <div class="content">
                    <span>G เพิ่มข้อมูลพนักงานAdmin</span>
                    <form action="add_pos7.php" method="post">
                        <div class="form-group">
                            <label for="admin_name">ชื่อ:</label>
                            <input type="text" id="admin_name" name="admin_name" required autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="admin_username">Username:</label>
                            <input type="text" id="admin_username" name="admin_username" required autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="admin_password">Password:</label>
                            <input type="password" id="admin_password" name="admin_password" required autocomplete="off">
                        </div>

                        <button type="submit" class="btn btn-save"><i class="fa fa-save"></i> บันทึก</button>
                        <button type="button" class="btn btn-cancel" onclick="location.href='show_position7.php'"><i
                                class="fas fa-times"></i>ยกเลิก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
// ตรวจสอบว่าฟอร์มถูกส่งหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = $_POST['admin_name']; // รับค่า admin_name จากฟอร์ม
    $admin_username = $_POST['admin_username']; // รับค่า admin_username จากฟอร์ม
    $admin_password = $_POST['admin_password']; // รับค่า admin_password จากฟอร์ม

    $db = new class_conn();
    $conn = $db->connect();

    // ดึง admin_no ล่าสุด
    $sql_last_no = "SELECT admin_no FROM tb_admin WHERE admin_no LIKE 'G_%' ORDER BY admin_no DESC LIMIT 1";
    $result = $conn->query($sql_last_no);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_no = $row['admin_no'];

        // แยกส่วนตัวเลขจาก admin_no
        $last_no_int = intval(substr($last_no, 2));

        // เพิ่มลำดับเลขต่อไป
        $new_no = 'G_' . str_pad($last_no_int + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $new_no = 'G_001';
    }

    // แทรกข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO tb_admin (admin_no, admin_name, admin_username, admin_password, position_id)
            VALUES ('$new_no', '$admin_name', '$admin_username', '$admin_password', '7')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Successful - New hr No: $new_no'); window.location.href = 'show_position7.php';</script>";
        exit();
    } else {
        echo "<div style='color: red; text-align: center;'>เกิดข้อผิดพลาด: " . $conn->error . "</div>";
    }

    $conn->close();
}
?>