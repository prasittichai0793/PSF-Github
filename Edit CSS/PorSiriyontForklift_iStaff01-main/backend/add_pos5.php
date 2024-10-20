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
            document.getElementById('hr_startDate').addEventListener('change', function() {
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

                document.getElementById('hr_exp').value = years + " ปี " + months + " เดือน " + days + " วัน";
                document.getElementById('hr_leaveDays').value = leaveDays + " วัน";
            });

            // คำนวณอายุ
            document.getElementById('hr_Date').addEventListener('change', function() {
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

                document.getElementById('hr_age').value = years + " ปี " + months + " เดือน " + days + " วัน";
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>เพิ่มข้อมูล</h1>
                <form action="add_pos5.php" method="post">
                    <div class="form-group">
                        <label for="hr_name">ชื่อ:</label>
                        <input type="text" id="hr_name" name="hr_name" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_startDate">วันเริ่มงาน:</label>
                        <input type="date" id="hr_startDate" name="hr_startDate" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_exp">อายุงาน:</label>
                        <input type="text" id="hr_exp" name="hr_exp" readonly>
                    </div>
                    <div class="form-group">
                        <label for="hr_idNumber">เลขบัตรประชาชน:</label>
                        <input type="text" id="hr_idNumber" name="hr_idNumber" required maxlength="13" pattern="\d{13}" title="กรุณากรอกเลขบัตรประชาชน 13 ตัวเท่านั้น">
                    </div>
                    <div class="form-group">
                        <label for="hr_phoneNumber">เบอร์โทรศัพท์:</label>
                        <input type="text" id="hr_phoneNumber" name="hr_phoneNumber" required maxlength="10" pattern="\d{10}" title="กรุณากรอกเบอร์โทรศัพท์ 10 ตัวเท่านั้น">
                    </div>
                    <div class="form-group">
                        <label for="hr_Date">วัน/เดือน/ปี เกิด:</label>
                        <input type="date" id="hr_Date" name="hr_Date" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_age">อายุ:</label>
                        <input type="text" id="hr_age" name="hr_age" readonly>
                    </div>
                    <div class="form-group">
                        <label for="hr_username">username:</label>
                        <input type="text" id="hr_username" name="hr_username" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_password">Password:</label>
                        <input type="password" id="hr_password" name="hr_password" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_gender">เพศ:</label>
                        <select id="hr_gender" name="hr_gender" required>
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
                    $hr_name = $_POST['hr_name'];
                    $hr_startDate = $_POST['hr_startDate'];
                    $hr_idNumber = $_POST['hr_idNumber'];
                    $hr_phoneNumber = $_POST['hr_phoneNumber'];
                    $hr_Date = $_POST['hr_Date'];
                    $hr_username = $_POST['hr_username'];
                    $hr_password = $_POST['hr_password'];
                    $hr_gender = $_POST['hr_gender'];

                    $db = new class_conn();
                    $conn = $db->connect();

                    // ดึง hr_no ล่าสุด
                    $sql_last_no = "SELECT hr_no FROM tb_hr WHERE hr_no LIKE 'A_%' ORDER BY hr_no DESC LIMIT 5";
                    $result = $conn->query($sql_last_no);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $last_no = $row['hr_no'];

                        // แยกส่วนตัวเลขจาก hr_no
                        $last_no_int = intval(substr($last_no, 2));

                        // เพิ่มลำดับเลขต่อไป
                        $new_no = 'E_' . str_pad($last_no_int + 1, 3, '0', STR_PAD_LEFT);
                    } else {
                        // กรณีที่ยังไม่มี hr_no ในระบบ ให้เริ่มต้นจาก G_001
                        $new_no = 'E_001';
                    }

                    // คำนวณอายุงาน
                    $startDate = new DateTime($hr_startDate);
                    $currentDate = new DateTime();
                    $interval = $startDate->diff($currentDate);
                    $hr_exp = $interval->y . " ปี " . $interval->m . " เดือน " . $interval->d . " วัน";
                    $totalWeeks = floor((($interval->y * 365 + $interval->m * 30 + $interval->d) / 7));
                    $leaveDays = floor($totalWeeks / 2);
                    
                    // แทรกข้อมูลลงในฐานข้อมูล
                    $sql = "INSERT INTO tb_hr (hr_no, hr_name, hr_startDate, hr_exp, hr_idNumber, hr_phoneNumber, hr_Date, hr_age, hr_username, hr_password, hr_gender,position_id)
                            VALUES ('$new_no', '$hr_name', '$hr_startDate', '$hr_exp', '$hr_idNumber', '$hr_phoneNumber', '$hr_Date', '$hr_age', '$hr_username', '$hr_password', '$hr_gender','5')";

                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Successful - New hr No: $new_no'); window.location.href = 'show_position5.php';</script>";
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
