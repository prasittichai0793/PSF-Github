<?php
include '../class_conn.php';

// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (!isset($_GET['id'])) {
    die("กรุณาระบุ ID ของผู้ใช้");
}

$user_id = $_GET['id'];

$db = new class_conn();
$conn = $db->connect();

// ดึงข้อมูลผู้ใช้จาก ID
$sql = "SELECT * FROM tb_user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("ไม่พบข้อมูลผู้ใช้");
}

$user = $result->fetch_assoc();

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $user_startDate = $_POST['user_startDate'];
    $user_idNumber = $_POST['user_idNumber'];
    $user_phoneNumber = $_POST['user_phoneNumber'];
    $user_Date = $_POST['user_Date'];
    $user_username = $_POST['user_username'];
    $user_password = $_POST['user_password'];
    $user_gender = $_POST['user_gender'];

    // คำนวณอายุงาน
    $startDate = new DateTime($user_startDate);
    $currentDate = new DateTime();
    $interval = $startDate->diff($currentDate);
    $user_exp = $interval->y . " ปี " . $interval->m . " เดือน " . $interval->d . " วัน";
    
    // คำนวณอายุ
    $birthDate = new DateTime($user_Date);
    $ageInterval = $birthDate->diff($currentDate);
    $user_age = $ageInterval->y . " ปี " . $ageInterval->m . " เดือน " . $ageInterval->d . " วัน";

    // คำนวณวันลา
    $totalWeeks = floor((($interval->y * 365 + $interval->m * 30 + $interval->d) / 7));
    $user_day = floor($totalWeeks / 2) . " วัน";

    // อัปเดตข้อมูลในฐานข้อมูล
    $update_sql = "UPDATE tb_user SET 
                   user_name = ?, 
                   user_startDate = ?, 
                   user_exp = ?, 
                   user_idNumber = ?, 
                   user_phoneNumber = ?, 
                   user_Date = ?, 
                   user_age = ?, 
                   user_username = ?, 
                   user_password = ?, 
                   user_gender = ?, 
                   user_day = ? 
                   WHERE user_id = ?";
    
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssssssi", 
        $user_name, $user_startDate, $user_exp, $user_idNumber, $user_phoneNumber, 
        $user_Date, $user_age, $user_username, $user_password, $user_gender, $user_day, $user_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Successful - แก้ไขสำเร็จ'); window.location.href = 'show_position6.php';</script>";
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
            document.getElementById('user_startDate').addEventListener('change', function() {
                calculateExperience();
            });

            // คำนวณอายุ
            document.getElementById('user_Date').addEventListener('change', function() {
                calculateAge();
            });

            // เรียกใช้ฟังก์ชันคำนวณเมื่อโหลดหน้า
            calculateExperience();
            calculateAge();
        });

        function calculateExperience() {
            var startDate = new Date(document.getElementById('user_startDate').value);
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
        }

        function calculateAge() {
            var birthDate = new Date(document.getElementById('user_Date').value);
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
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>แก้ไขข้อมูลพนักงาน</h1>
                <form action="edit_pos6.php?id=<?php echo $user_id; ?>" method="post">
                    <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($return_url); ?>">
                    <div class="form-group">
                        <label for="user_name">ชื่อ:</label>
                        <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user['user_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="user_startDate">วันเริ่มงาน:</label>
                        <input type="date" id="user_startDate" name="user_startDate" value="<?php echo $user['user_startDate']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="user_exp">อายุงาน:</label>
                        <input type="text" id="user_exp" name="user_exp" value="<?php echo $user['user_exp']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="user_leaveDays">วันลา:</label>
                        <input type="text" id="user_leaveDays" name="user_leaveDays" value="<?php echo $user['user_day']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="user_idNumber">เลขบัตรประชาชน:</label>
                        <input type="text" id="user_idNumber" name="user_idNumber" value="<?php echo htmlspecialchars($user['user_idNumber']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="user_phoneNumber">เบอร์โทรศัพท์:</label>
                        <input type="text" id="user_phoneNumber" name="user_phoneNumber" value="<?php echo htmlspecialchars($user['user_phoneNumber']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="user_Date">วันเกิด:</label>
                        <input type="date" id="user_Date" name="user_Date" value="<?php echo $user['user_Date']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="user_age">อายุ:</label>
                        <input type="text" id="user_age" name="user_age" value="<?php echo $user['user_age']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="user_username">ชื่อผู้ใช้:</label>
                        <input type="text" id="user_username" name="user_username" value="<?php echo htmlspecialchars($user['user_username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="user_password">รหัสผ่าน:</label>
                        <input type="password" id="user_password" name="user_password" value="<?php echo htmlspecialchars($user['user_password']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="user_gender">เพศ:</label>
                        <select id="user_gender" name="user_gender" required>
                            <option value="ชาย" <?php echo ($user['user_gender'] == "ชาย") ? "selected" : ""; ?>>ชาย</option>
                            <option value="หญิง" <?php echo ($user['user_gender'] == "หญิง") ? "selected" : ""; ?>>หญิง</option>
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
