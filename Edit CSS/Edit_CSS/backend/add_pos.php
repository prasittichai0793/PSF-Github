<?php include '../class_conn.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($_GET['edit_id']) ? 'แก้ไขแผนก' : 'เพิ่มข้อมูลแผนก'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        /* add_all.css */
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

<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1><?php echo isset($_GET['edit_id']) ? 'แก้ไขแผนก' : 'เพิ่มแผนก'; ?></h1>
                <?php
                // สร้างการเชื่อมต่อกับฐานข้อมูล
                $db = new class_conn();
                $conn = $db->connect();

                // Check if we're in edit mode
                $edit_mode = false;
                $position_id = "";
                $position_type = "";
                $position_name = "";

                if (isset($_GET['edit_id'])) {
                    $edit_mode = true;
                    $position_id = $_GET['edit_id'];

                    // Fetch the position data
                    $sql = "SELECT * FROM tb_position WHERE position_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $position_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $position_type = $row['position_type'];
                        $position_name = $row['position_name'];
                    }
                    $stmt->close();
                } else {
                    // ตรวจสอบ position_type ล่าสุด
                    $sql_last_no = "SELECT position_type FROM tb_position ORDER BY position_type DESC LIMIT 1";
                    $result = $conn->query($sql_last_no);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $last_no = $row['position_type'];

                        // เพิ่มตัวอักษรถัดไป
                        $new_no = chr(ord($last_no) + 1);

                        // ตรวจสอบไม่ให้เกิน 'Z'
                        if ($new_no > 'Z') {
                            $new_no = 'A';
                        }
                    } else {
                        $new_no = 'A'; // เริ่มที่ A ถ้าไม่มีข้อมูล
                    }

                    $position_type = $new_no;
                }
                ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="position_type">ประเภทแผนก:</label>
                        <input type="text" id="position_type" name="position_type" value="<?php echo $position_type; ?>" <?php echo $edit_mode ? 'readonly' : ''; ?>>
                    </div>

                    <div class="form-group">
                        <label for="position_name">ชื่อแผนก:</label>
                        <input type="text" id="position_name" name="position_name" value="<?php echo $position_name; ?>" required>
                    </div>

                    <button type="submit" class="btn btn-save"><i class="fa fa-save"></i> บันทึก</button>
                    <a href="show_position.php" class="btn">ยกเลิก</a>
                </form>
            </header>
        </div>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $position_type = $_POST['position_type'];
    $position_name = $_POST['position_name'];

    if ($edit_mode) {
        // Update existing position
        $sql = "UPDATE tb_position SET position_name = ? WHERE position_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $position_name, $position_id);
    } else {
        // Insert new position
        $sql = "INSERT INTO tb_position (position_type, position_name) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $position_type, $position_name);
    }

    if ($stmt->execute()) {
        echo "<script>alert('บันทึกข้อมูลสำเร็จ'); window.location.href='show_position.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');</script>";
    }
    $stmt->close();

    // ปิดการเชื่อมต่อฐานข้อมูล
    $conn->close();
}
?>
