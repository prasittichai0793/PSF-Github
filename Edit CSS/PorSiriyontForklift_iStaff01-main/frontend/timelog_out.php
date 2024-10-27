<?php
session_start();
include '../class_conn.php';

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'user') {
    header("Location: ../index.php");
    exit();
}

$conn = new class_conn();
$connection = $conn->connect();

$username = $_SESSION['username'];

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$query = "SELECT user_id, user_no, user_name, position_id FROM tb_user WHERE user_username = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);
$user_id = $user_data['user_id'];

// ตรวจสอบว่าได้ออกงานไปแล้วหรือไม่ - แก้ไข query ให้ใช้ DATE(NOW())
$check_logout_query = "SELECT timelog_status FROM tb_timelog 
                      WHERE user_id = ? 
                      AND DATE(NOW()) = CURDATE() 
                      AND timelog_status = 'ออกงานแล้ว'";
$stmt_check = mysqli_prepare($connection, $check_logout_query);
mysqli_stmt_bind_param($stmt_check, "s", $user_id);
mysqli_stmt_execute($stmt_check);
$check_result = mysqli_stmt_get_result($stmt_check);
$already_logged = mysqli_num_rows($check_result) > 0;

// ตัวแปรสำหรับข้อความข้อผิดพลาด
$error_message = "";

// ตรวจสอบว่ามีข้อมูลจาก POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$already_logged) {
    // ดึง data_id ตาม user_id จาก tb_data
    $data_query = "SELECT data_id FROM tb_data WHERE user_id = ?";
    $stmt_data = mysqli_prepare($connection, $data_query);
    mysqli_stmt_bind_param($stmt_data, "s", $user_id);
    mysqli_stmt_execute($stmt_data);
    $data_result = mysqli_stmt_get_result($stmt_data);
    
    if (mysqli_num_rows($data_result) > 0) {
        $data_row = mysqli_fetch_assoc($data_result);
        $data_id = $data_row['data_id'];

        // อัปเดตเฉพาะเวลาออกงาน โดยใช้ TIME(NOW())
        $query_update = "UPDATE tb_timelog 
                        SET timelog_date = CURDATE(), 
                            timelog_out = TIME(NOW()), 
                            timelog_status = 'ออกงานแล้ว' 
                        WHERE user_id = ? AND data_id = ?";
        $stmt_update = mysqli_prepare($connection, $query_update);
        mysqli_stmt_bind_param($stmt_update, "ss", $user_id, $data_id);

        if (mysqli_stmt_execute($stmt_update)) {
            header("Location: show_profile.php?success=1");
            exit();
        } else {
            $error_message = mysqli_error($connection);
        }
    } else {
        $error_message = "ไม่พบ data_id สำหรับ user_id นี้";
    }
}
?>
<?php include 'sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลส่วนตัว</title>
    <style>
        .profile-container {
            background-color: #FFDEDE;
            padding: 20px;
            border-radius: 20px;
            max-width: 800px;
            margin: 40px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-container2 {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        

        .profile-image {
            border-radius: 20px;
            overflow: hidden;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .profile-details {
            flex-grow: 1;
        }

        .profile-details h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .timelog_in {
            flex-grow: 1;
        }

        .timelog_in h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .profile-info {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .profile-info p {
            margin: 10px 0;
            color: #555;
        }

        .timelog_in-info {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        .timelog_in-info p {
            margin: 10px 0;
            color: #555;
        }

        .info-label {
            font-weight: bold;
            float: left;
            color: #333;
            width: 120px;
            display: inline-block;
        }

        .info-label2 {
            font-weight: bold;
            color: #333;
            width: 180px;
            display: inline-block;
        }

        #current-time {
            font-size: 2em;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .time-log-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .time-log-button:hover {
            background-color: #45a049;
        }

        .timelog_in-info {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            text-align: center;
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
        }

        .time-button-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 10px;
        }

        .time-log-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .time-log-button.disabled {
            background-color: #cccccc;
            cursor: not-allowed;
            pointer-events: none;
        }

        .time-log-button:hover:not(.disabled) {
            background-color: #45a049;
        }

        .time-log-out {
            background-color: #ff4444;
        }

        .time-log-out:hover:not(.disabled) {
            background-color: #cc0000;
        }

        .time-status {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
        .warning {
            color: #ff4444;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
        
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }
        
        .confirm-button {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            margin: 0 10px;
        }
        
        .confirm-no {
            background-color: #6c757d;
        }
        
        .confirm-no:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <div class="timelog_in">
            <div class="timelog_in-info">
                <h2>ข้อมูลส่วนตัว</h2>
                <div class="profile-info">
                    <p><span class="info-label">รหัสพนักงาน:</span> <?php echo $user_data['user_no']; ?></p>
                    <p><span class="info-label">ชื่อ-นามสกุล:</span> <?php echo $user_data['user_name']; ?></p>
                    <p><span class="info-label">วันที่ทำการ:</span> <span id="current-date"></span></p>
                    <p><span class="info-label">รหัสตำแหน่ง:</span> <?php echo $user_data['position_id']; ?></p>
                </div>
                
                <?php if ($already_logged): ?>
                    <div class="warning">คุณได้บันทึกเวลาออกงานของวันนี้ไปแล้ว</div>
                    <div class="button-container">
                        <a href="show_profile.php" class="confirm-button confirm-no">กลับ</a>
                    </div>
                <?php else: ?>
                    <div class="time-button-container">
                        <form action="" method="POST">
                            <button type="submit" id="logoutButton" class="time-log-button time-log-out disabled">บันทึกเวลาออกงาน</button>
                        </form>
                    </div>
                    <div id="timeStatus" class="time-status"></div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const currentTimeStr = `${hours}:${minutes}:${seconds}`;

            // แปลงเวลาปัจจุบันเป็นวินาที
            const currentTimeInSeconds = (parseInt(hours) * 3600) + (parseInt(minutes) * 60) + parseInt(seconds);
            const targetTimeInSeconds = (21 * 3600) + (10 * 60); // 5:15 AM

            <?php if (!$already_logged): ?>
            // ตรวจสอบเวลาสำหรับปุ่มออกงาน
            const logoutButton = document.getElementById('logoutButton');
            const timeStatus = document.getElementById('timeStatus');

            if (currentTimeInSeconds >= targetTimeInSeconds) {
                logoutButton.classList.remove('disabled');
                timeStatus.textContent = 'สามารถบันทึกเวลาออกงานได้';
                timeStatus.style.color = '#4CAF50';
            } else {
                logoutButton.classList.add('disabled');
                const remainingSeconds = targetTimeInSeconds - currentTimeInSeconds;
                const remainingHours = Math.floor(remainingSeconds / 3600);
                const remainingMinutes = Math.floor((remainingSeconds % 3600) / 60);
                const remainingSecs = remainingSeconds % 60;

                timeStatus.textContent = `สามารถบันทึกเวลาออกงานได้ในอีก ${String(remainingHours).padStart(2, '0')}:${String(remainingMinutes).padStart(2, '0')}:${String(remainingSecs).padStart(2, '0')}`;
                timeStatus.style.color = '#ff4444';
            }
            <?php endif; ?>
        }

        // อัพเดทเวลาทุกๆ 1 วินาที
        updateTime();
        setInterval(updateTime, 1000);

        function updateDate() {
            const now = new Date();
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const year = now.getFullYear();

            const currentDateStr = `${day}/${month}/${year}`;
            document.getElementById('current-date').textContent = currentDateStr;
        }

        // เรียกฟังก์ชันเพื่ออัพเดทวันที่เมื่อโหลดหน้าเว็บ
        updateDate();
    </script>
</body>
</html>