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

// ดึงข้อมูลผู้ใช้จากฐานข้อมูลพร้อมชื่อตำแหน่ง
$username = $_SESSION['username'];
$query = "SELECT u.user_no, u.user_name, u.user_age, u.user_startDate, u.user_day, 
          p.position_name 
          FROM tb_user u
          LEFT JOIN tb_position p ON u.position_id = p.position_id
          WHERE u.user_username = ?";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);
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
            display: flex;
            align-items: flex-start;
            background-color: #FFDEDE;
            padding: 20px;
            border-radius: 20px;
            max-width: 800px;
            margin: 30px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-container2 {
            display: flex;
            align-items: flex-start;
            background-color: #FFDEDE;
            padding: 20px;
            border-radius: 20px;
            max-width: 800px;
            margin: 40px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-details">
            <h2>ข้อมูลส่วนตัว</h2>
            <div class="profile-info">
                <p><span class="info-label">รหัสพนักงาน:</span> <?php echo $user_data['user_no']; ?></p>
                <p><span class="info-label">ชื่อ-นามสกุล:</span> <?php echo $user_data['user_name']; ?></p>
                <p><span class="info-label">อายุ:</span> <?php echo $user_data['user_age']; ?> ปี</p>
                <p><span class="info-label">วันที่เริ่มงาน:</span> <?php echo date('d/m/Y', strtotime($user_data['user_startDate'])); ?></p>
                <p><span class="info-label">วันลา:</span> <?php echo $user_data['user_day']; ?></p>
                <p><span class="info-label">ตำแหน่ง:</span> <?php echo $user_data['position_name']; ?></p>
            </div>
        </div>
    </div>
    <div class="profile-container2">
        <div class="timelog_in">
            <div class="timelog_in-info">
                <div>
                    <p><span class="info-label2">เวลาปัจจุบัน</span></p>
                    <div id="current-time">00:00:00</div>
                    <div class="time-button-container">
                        <a href="timelog_in.php" class="time-log-button">บันทึกเวลาเข้างาน</a>
                        <a href="timelog_out.php" id="logoutButton" class="time-log-button time-log-out disabled">บันทึกเวลาออกงาน</a>
                    </div>
                    <div id="timeStatus" class="time-status"></div>
                </div>
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
            
            document.getElementById('current-time').textContent = currentTimeStr;

            // ตรวจสอบเวลาสำหรับปุ่มออกงาน
            const logoutButton = document.getElementById('logoutButton');
            const timeStatus = document.getElementById('timeStatus');
            
            // แปลงเวลาปัจจุบันเป็นวินาที
            const currentTimeInSeconds = (hours * 3600) + (minutes * 60) + Number(seconds);
            // แปลงเวลา 12:55:00 เป็นวินาที
            const targetTimeInSeconds = (21 * 3600) + (10 * 60);

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
        }

        // อัพเดทเวลาทุกๆ 1 วินาที
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>
</html>