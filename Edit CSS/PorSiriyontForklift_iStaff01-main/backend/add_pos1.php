<?php include '../class_conn.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A_เพิ่มข้อมูลพนักงานขับรถเครน</title>
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
            document.getElementById('user_startDate').addEventListener('change', function() {
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

                // คำนวณอายุงานเป็นสัปดาห์
                var totalWeeks = Math.floor((years * 12 * 30 + months * 30 + days) / 7);
                var leaveDays = Math.floor(totalWeeks / 2);

                document.getElementById('user_exp').value = years + " ปี " + months + " เดือน " + days + " วัน";
                document.getElementById('user_leaveDays').value = leaveDays + " วัน";
            });

            // คำนวณอายุ
            document.getElementById('user_Date').addEventListener('change', function() {
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

                document.getElementById('user_age').value = years + " ปี " + months + " เดือน " + days + " วัน";
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>เพิ่มข้อมูล</h1>
                <form action="add_pos1.php" method="post">
                    <div class="form-group">
                        <label for="user_name">ชื่อ:</label>
                        <input type="text" id="user_name" name="user_name" required>
                    </div>
                    <div class="form-group">
                        <label for="user_startDate">วันเริ่มงาน:</label>
                        <input type="date" id="user_startDate" name="user_startDate" required>
                    </div>
                    <div class="form-group">
                        <label for="user_exp">อายุงาน:</label>
                        <input type="text" id="user_exp" name="user_exp" readonly>
                    </div>
                    <div class="form-group">
                        <label for="user_leaveDays">วันลา:</label>
                        <input type="text" id="user_leaveDays" name="user_leaveDays" readonly>
                    </div>
                    <div class="form-group">
                        <label for="user_idNumber">เลขบัตรประชาชน:</label>
                        <input type="text" id="user_idNumber" name="user_idNumber" required maxlength="13" pattern="\d{13}" title="กรุณากรอกเลขบัตรประชาชน 13 ตัวเท่านั้น">
                    </div>
                    <div class="form-group">
                        <label for="user_phoneNumber">เบอร์โทรศัพท์:</label>
                        <input type="text" id="user_phoneNumber" name="user_phoneNumber" required maxlength="10" pattern="\d{10}" title="กรุณากรอกเบอร์โทรศัพท์ 10 ตัวเท่านั้น">
                    </div>
                    <div class="form-group">
                        <label for="user_Date">วัน/เดือน/ปี เกิด:</label>
                        <input type="date" id="user_Date" name="user_Date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="user_age">อายุ:</label>
                        <input type="text" id="user_age" name="user_age" readonly>
                    </div>
                    <div class="form-group">
                        <label for="user_username">Username:</label>
                        <input type="text" id="user_username" name="user_username" required>
                    </div>
                    <div class="form-group">
                        <label for="user_password">Password:</label>
                        <input type="password" id="user_password" name="user_password" required>
                    </div>
                    <div class="form-group">
                        <label for="user_gender">เพศ:</label>
                        <select id="user_gender" name="user_gender" required>
                            <option value="male">ชาย</option>
                            <option value="female">หญิง</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-save"><i class="fa fa-save"></i> บันทึก</button>
                </form>
            </header>
            <div class="content">
                <?php
                // ตรวจสอบว่าฟอร์มถูกส่งหรือไม่
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $user_name = $_POST['user_name'];
                    $user_startDate = $_POST['user_startDate'];
                    $user_idNumber = $_POST['user_idNumber'];
                    $user_phoneNumber = $_POST['user_phoneNumber'];
                    $user_Date = $_POST['user_Date'];
                    $user_username = $_POST['user_username'];
                    $user_password = $_POST['user_password'];
                    $user_gender = $_POST['user_gender'];
                    $user_age = $_POST['user_age'];  

                    $db = new class_conn();
                    $conn = $db->connect();

                    // ดึง user_no ล่าสุด
                    $sql_last_no = "SELECT user_no FROM tb_user WHERE user_no LIKE 'A_%' ORDER BY user_no DESC LIMIT 1";
                    $result = $conn->query($sql_last_no);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $last_no = $row['user_no'];

                        // แยกส่วนตัวเลขจาก user_no
                        $last_no_int = intval(substr($last_no, 2));

                        // เพิ่มลำดับเลขต่อไป
                        $new_no = 'A_' . str_pad($last_no_int + 1, 3, '0', STR_PAD_LEFT);
                    } else {
                        // กรณีที่ยังไม่มี user_no ในระบบ ให้เริ่มต้นจาก G_001
                        $new_no = 'A_001';
                    }

                    // คำนวณอายุงาน
                    $startDate = new DateTime($user_startDate);
                    $currentDate = new DateTime();
                    $interval = $startDate->diff($currentDate);
                    $user_exp = $interval->y . " ปี " . $interval->m . " เดือน " . $interval->d . " วัน";
                    $totalWeeks = floor((($interval->y * 365 + $interval->m * 30 + $interval->d) / 7));
                    $leaveDays = floor($totalWeeks / 2). " วัน";
                    
                    // แทรกข้อมูลลงในฐานข้อมูล
                    $sql = "INSERT INTO tb_user (user_no, user_name, user_startDate, user_exp, user_idNumber, user_phoneNumber, user_Date, user_age, user_username, user_password, user_gender, user_day,position_id)
                            VALUES ('$new_no', '$user_name', '$user_startDate', '$user_exp', '$user_idNumber', '$user_phoneNumber', '$user_Date', '$user_age', '$user_username', '$user_password', '$user_gender', '$leaveDays','1')";

                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Successful - New user No: $new_no'); window.location.href = 'show_position1.php';</script>";
                        exit(); 
                    } else {
                        echo "<div style='color: red; text-align: center;'>เกิดข้อผิดพลาด: " . $conn->error . "</div>";
                    }

                    $conn->close();
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>