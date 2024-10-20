<?php
include '../class_conn.php';

// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (!isset($_GET['id'])) {
    die("กรุณาระบุ ID ของผู้ใช้");
}

$hr_id = $_GET['id'];

$db = new class_conn();
$conn = $db->connect();

// ดึงข้อมูลผู้ใช้จาก ID
$sql = "SELECT * FROM tb_hr WHERE hr_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hr_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("ไม่พบข้อมูลผู้ใช้");
}

$hr = $result->fetch_assoc();

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hr_name = $_POST['hr_name'];
    $hr_startDate = $_POST['hr_startDate'];
    $hr_idNumber = $_POST['hr_idNumber'];
    $hr_phoneNumber = $_POST['hr_phoneNumber'];
    $hr_Date = $_POST['hr_Date'];
    $hr_username = $_POST['hr_username'];
    $hr_password = $_POST['hr_password'];
    $hr_gender = $_POST['hr_gender'];

    // คำนวณอายุงาน
    $startDate = new DateTime($hr_startDate);
    $currentDate = new DateTime();
    $interval = $startDate->diff($currentDate);
    $hr_exp = $interval->y . " ปี " . $interval->m . " เดือน " . $interval->d . " วัน";
    
    // คำนวณอายุ
    $birthDate = new DateTime($hr_Date);
    $ageInterval = $birthDate->diff($currentDate);
    $hr_age = $ageInterval->y . " ปี " . $ageInterval->m . " เดือน " . $ageInterval->d . " วัน";

    // คำนวณวันลา
    $totalWeeks = floor((($interval->y * 365 + $interval->m * 30 + $interval->d) / 7));
    $hr_day = floor($totalWeeks / 2) . " วัน";

    // อัปเดตข้อมูลในฐานข้อมูล
    $update_sql = "UPDATE tb_hr SET 
                   hr_name = ?, 
                   hr_startDate = ?, 
                   hr_exp = ?, 
                   hr_idNumber = ?, 
                   hr_phoneNumber = ?, 
                   hr_Date = ?, 
                   hr_age = ?, 
                   hr_username = ?, 
                   hr_password = ?, 
                   hr_gender = ?, 
                   hr_day = ? 
                   WHERE hr_id = ?";
    
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssssssi", 
        $hr_name, $hr_startDate, $hr_exp, $hr_idNumber, $hr_phoneNumber, 
        $hr_Date, $hr_age, $hr_username, $hr_password, $hr_gender, $hr_day, $hr_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Successful - แก้ไขสำเร็จ'); window.location.href = 'show_position5.php';</script>";
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
    <title>แก้ไขข้อมูลพนักงาน</title>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // คำนวณอายุงาน
            document.getElementById('hr_startDate').addEventListener('change', function() {
                calculateExperience();
            });

            // คำนวณอายุ
            document.getElementById('hr_Date').addEventListener('change', function() {
                calculateAge();
            });

            // เรียกใช้ฟังก์ชันคำนวณเมื่อโหลดหน้า
            calculateExperience();
            calculateAge();
        });

        function calculateExperience() {
            var startDate = new Date(document.getElementById('hr_startDate').value);
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
        }

        function calculateAge() {
            var birthDate = new Date(document.getElementById('hr_Date').value);
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
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>แก้ไขข้อมูลพนักงาน</h1>
                <form action="edit_pos5.php?id=<?php echo $hr_id; ?>" method="post">
                    <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($return_url); ?>">
                    <div class="form-group">
                        <label for="hr_name">ชื่อ:</label>
                        <input type="text" id="hr_name" name="hr_name" value="<?php echo htmlspecialchars($hr['hr_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_startDate">วันเริ่มงาน:</label>
                        <input type="date" id="hr_startDate" name="hr_startDate" value="<?php echo $hr['hr_startDate']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_exp">อายุงาน:</label>
                        <input type="text" id="hr_exp" name="hr_exp" value="<?php echo $hr['hr_exp']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="hr_leaveDays">วันลา:</label>
                        <input type="text" id="hr_leaveDays" name="hr_leaveDays" value="<?php echo $hr['hr_day']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="hr_idNumber">เลขบัตรประชาชน:</label>
                        <input type="text" id="hr_idNumber" name="hr_idNumber" value="<?php echo htmlspecialchars($hr['hr_idNumber']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_phoneNumber">เบอร์โทรศัพท์:</label>
                        <input type="text" id="hr_phoneNumber" name="hr_phoneNumber" value="<?php echo htmlspecialchars($hr['hr_phoneNumber']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_Date">วันเกิด:</label>
                        <input type="date" id="hr_Date" name="hr_Date" value="<?php echo $hr['hr_Date']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_age">อายุ:</label>
                        <input type="text" id="hr_age" name="hr_age" value="<?php echo $hr['hr_age']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="hr_username">ชื่อผู้ใช้:</label>
                        <input type="text" id="hr_username" name="hr_username" value="<?php echo htmlspecialchars($hr['hr_username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_password">รหัสผ่าน:</label>
                        <input type="password" id="hr_password" name="hr_password" value="<?php echo htmlspecialchars($hr['hr_password']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hr_gender">เพศ:</label>
                        <select id="hr_gender" name="hr_gender" required>
                            <option value="ชาย" <?php echo ($hr['hr_gender'] == "ชาย") ? "selected" : ""; ?>>ชาย</option>
                            <option value="หญิง" <?php echo ($hr['hr_gender'] == "หญิง") ? "selected" : ""; ?>>หญิง</option>
                        </select>
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
