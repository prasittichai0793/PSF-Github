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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลส่วนตัว</title>
    <link rel="icon" href="../images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <?php include 'sidebar.php'; ?>
    <style>
        body {
            margin: 0;
            font-family: Verdana, sans-serif;
        }

        .display-container {
            position: fixed;
            top: 0px;
            left: 250px;
            right: 0px;
            bottom: 0px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            transition: left 0.3s ease;
            background: none;
        }

        .display-content {
            background: none;
            min-width: 290px;
            width: 70%;
            max-width: 900px;
            height: auto;
            border-radius: 30px;
        }

        .display-container-head span {
            padding-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            display: flex;
            justify-content: center;
        }

        .content1 {
            padding: 20px;
            border-radius: 20px;
            font-size: 16px;
            background-color: #FFDEDE;
        }

        .profile-content1 {
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .profile-image {
            margin-right: 20px;
        }

        .profile-details {
            flex-grow: 1;
        }

        .free-space {
            width: 100%;
            height: 70px;
            background: none;
        }

        .content2 {
            padding: 20px;
            border-radius: 20px;
            font-size: 16px;
            background-color: #FFDEDE;
        }

        .profile-content2 {
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .timelog_in-info {
            text-align: center;
        }

        .info-label2 {
            font-size: 16px;
        }

        #current-time {
            font-size: 24px;
            font-weight: bold;
            padding: 15px 0px 30px 0px;
        }

        .time-log-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .time-log-button:hover {
            background-color: #45a049;
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
            font-size: 16px;
            color: #666;
            margin-top: 25px;
        }

        @media screen and (max-width: 930px) {
            .display-container {
                position: fixed;
                top: 0px;
                left: 60px;
                right: 0px;
                bottom: 0px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                transition: left 0.3s ease;
                background: none;
            }

            .display-content {
                background: none;
                height: auto;
                border-radius: 30px;
            }

            .display-container-head span {
                padding-bottom: 20px;
                font-size: 20px;
                font-weight: bold;
                display: flex;
                justify-content: center;
            }

            .content1 {
                padding: 10px;
                border-radius: 15px;
                font-size: 14px;
                background-color: #FFDEDE;
            }

            .profile-content1 {
                padding: 20px;
                border-radius: 10px;
                background-color: #ffffff;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
            }

            .free-space {
                width: 100%;
                height: 40px;
                background: none;
            }

            .content2 {
                padding: 10px;
                border-radius: 15px;
                background-color: #FFDEDE;
            }

            .profile-content2 {
                padding: 20px;
                border-radius: 10px;
                background-color: #ffffff;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
            }

            .profile-details h2 {
                font-size: 16px;
            }

            .time-button-container {
                display: flex;
                flex-direction: column;
            }

            #current-time {
                font-size: 18px;
                font-weight: bold;
                padding: 10px 0px;
            }

            .profile-image {
                height: 40px;
                width: 40px;
            }

            .time-log-button {
                font-size: 14px;
                margin-bottom: 5px;
            }

            .time-status {
                font-size: 14px;
                color: #666;
                margin-top: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="display-container">
        <div class="display-content">
            <div class="content1">
                <div class="display-container-head">
                    <span>ข้อมูลส่วนตัว</span>
                </div>

                <div class="profile-content1">
                    <div class="profile-details">
                        <h2>ข้อมูลส่วนตัว</h2>
                        <div class="profile-info">
                            <p><span class="info-label">รหัสพนักงาน:</span> <?php echo $user_data['user_no']; ?></p>
                            <p><span class="info-label">ชื่อ-นามสกุล:</span> <?php echo $user_data['user_name']; ?></p>
                            <p><span class="info-label">อายุ:</span> <?php echo $user_data['user_age']; ?> ปี</p>
                            <p><span class="info-label">วันที่เริ่มงาน:</span>
                                <?php echo date('d/m/Y', strtotime($user_data['user_startDate'])); ?></p>
                            <p><span class="info-label">วันลา:</span> <?php echo $user_data['user_day']; ?></p>
                            <p><span class="info-label">ตำแหน่ง:</span> <?php echo $user_data['position_name']; ?></p>
                        </div>
                    </div>
                </div>

            </div>

            <div class=" free-space"></div>

            <div class="content2">
                <div class="profile-content2">
                    <div class="timelog_in">
                        <div class="timelog_in-info">
                            <span class="info-label2">เวลาปัจจุบัน</span>
                            <div id="current-time">00:00:00</div>
                            <div class="time-button-container">
                                <a href="timelog_in.php" class="time-log-button">บันทึกเวลาเข้างาน</a>
                                <a href="timelog_out.php" id="logoutButton"
                                    class="time-log-button time-log-out disabled">บันทึกเวลาออกงาน</a>
                            </div>
                            <div id="timeStatus" class="time-status"></div>
                        </div>
                    </div>
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
            const targetTimeInSeconds = (17 * 3600) + (00 * 60);

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