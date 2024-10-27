<?php include '../class_conn.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G_เพิ่มข้อมูลadmin</title>
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
        .form-group select {
            width: 100%;
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
    
    <!-- JavaScript สำหรับการคำนวณอายุและอายุงาน -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // คำนวณอายุงาน
            document.getElementById('admin_startDate').addEventListener('change', function() {
                var startDate = new Date(this.value);
                var currentDate = new Date();
                
                var years = currentDate.getFullYear() - startDate.getFullYear();
                var months = currentDate.getMonth() - startDate.getMonth();
                var days = currentDate.getDate() - startDate.getDate();

                if (days < 0) {
                    months--;
                    days += new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();
                }
                if (months < 0) {
                    years--;
                    months += 12;
                }

                document.getElementById('admin_exp').value = years + " ปี " + months + " เดือน " + days + " วัน";
            });

            // คำนวณอายุ
            document.getElementById('admin_Date').addEventListener('change', function() {
                var birthDate = new Date(this.value);
                var currentDate = new Date();
                
                var years = currentDate.getFullYear() - birthDate.getFullYear();
                var months = currentDate.getMonth() - birthDate.getMonth();
                var days = currentDate.getDate() - birthDate.getDate();

                if (days < 0) {
                    months--;
                    days += new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();
                }
                if (months < 0) {
                    years--;
                    months += 12;
                }

                document.getElementById('admin_age').value = years + " ปี " + months + " เดือน " + days + " วัน";
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>G_เพิ่มข้อมูลadmin</h1>
                <form action="add_pos7.php" method="post">
                    <div class="form-group">
                        <label for="admin_name">ชื่อ:</label>
                        <input type="text" id="admin_name" name="admin_name" required>
                    </div>
                    <div class="form-group">
                        <label for="admin_adminname">username:</label>
                        <input type="text" id="admin_username" name="admin_username" required>
                    </div>
                    <div class="form-group">
                        <label for="admin_password">Password:</label>
                        <input type="password" id="admin_password" name="admin_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-save"><i class="fa fa-save"></i> บันทึก</button>
                </form>
            </header>
            <div class="content">
                <?php
                // ตรวจสอบว่าฟอร์มถูกส่งหรือไม่
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $admin_name = $_POST['admin_name'];
                    $admin_username = $_POST['admin_username'];
                    $admin_password = $_POST['admin_password'];
                    $position_id = $_POST['position_id'];

                    // เชื่อมต่อฐานข้อมูล
                    $db = new class_conn();
                    $conn = $db->connect();

                    // ดึง user_no ล่าสุด
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
                        // กรณีที่ยังไม่มี admin_no ในระบบ ให้เริ่มต้นจาก G_001
                        $new_no = 'G_001';
                    }

                    // คำนวณอายุงาน
                    $startDate = new DateTime($admin_startDate);
                    $currentDate = new DateTime();
                    $interval = $startDate->diff($currentDate);
                    $admin_exp = $interval->y . " ปี " . $interval->m . " เดือน " . $interval->d . " วัน";

                    // คำนวณอายุ
                    $birthDate = new DateTime($admin_Date);
                    $ageInterval = $birthDate->diff($currentDate);
                    $admin_age = $ageInterval->y . " ปี " . $ageInterval->m . " เดือน " . $ageInterval->d . " วัน";
                    // SQL Query
                    $sql = "INSERT INTO tb_admin (admin_no, admin_name, admin_username, admin_password, position_id) 
                            VALUES ('$new_no','$admin_name', '$admin_username', '$admin_password', '7')";

                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Successful - New admin No: $new_no'); window.location.href = 'show_position7.php';</script>";
                        exit(); // หยุดการทำงานหลังจาก redirect
                    } else {
                        echo "<p>ข้อผิดพลาด: " . $conn->error . "</p>";
                    }
                    $conn->close();
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>