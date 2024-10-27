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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการบันทึกเวลาออกงาน</title>
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
            font-size: 16px;
            /* font-weight: bold; */
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
            margin-top: 10px;
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
        }

        .button-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }

        .confirm-button {
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            text-decoration: none;
            color: white;
            flex-direction: column;
        }

        .confirm-yes {
            background-color: #4CAF50;
            margin-right: 10px;
        }

        .confirm-no {
            background-color: #ff4444;
            margin-left: 10px;
        }

        .warning {
            color: #ff4444;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

        .info-row {
            font-size: 16px;
            margin: 10px 0px;
        }

        .time-button-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
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
                font-size: 16px;
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
                    <span>ยืนยันการบันทึกเวลาออกงาน</span>
                </div>

                <div class="profile-content1">
                    <div class="profile-details">
                        <div class="profile-info">
                            <div class="confirm-info">
                                <div class="info-row">
                                    <span class="info-label">รหัสพนักงาน:</span>
                                    <span><?php echo $user_data['user_no']; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">ชื่อ-นามสกุล:</span>
                                    <span><?php echo $user_data['user_name']; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">วันที่ทำการ:</span>
                                    <span id="current-date"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">รหัสตำแหน่ง:</span>
                                    <span id="current-date"><?php echo $user_data['position_id']; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">เวลาปัจจุบัน:</span>
                                    <span id="current-time"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-container">
                    <?php if ($already_logged): ?>
                        <div class="show-span">
                            <div class="warning">คุณได้บันทึกเวลาออกงานของวันนี้ไปแล้ว</div>
                            <div class="button-container">
                                <a href="show_profile.php" class="confirm-button confirm-no">กลับ</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="time-button-container">
                            <div id="timeStatus" class="time-status"></div>
                            <form action="" method="POST">
                                <button type="submit" id="logoutButton"
                                    class="time-log-button time-log-out disabled">บันทึกเวลาออกงาน</button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error_message)): ?>
                        <p style="color: red;"><?php echo $error_message; ?></p>
                    <?php endif; ?>
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

            document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;

            // แปลงเวลาปัจจุบันเป็นวินาที
            const currentTimeInSeconds = (parseInt(hours) * 3600) + (parseInt(minutes) * 60) + parseInt(seconds);
            const targetTimeInSeconds = (17 * 3600) + (10 * 60); // 5:15 AM

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