<?php
session_start();
include '../class_conn.php';

// ตั้งค่า timezone เป็นประเทศไทย
date_default_timezone_set('Asia/Bangkok');

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'user') {
    header("Location: ../index.php");
    exit();
}

$conn = new class_conn();
$connection = $conn->connect();

// ดึงข้อมูลผู้ใช้
$username = $_SESSION['username'];
$query = "SELECT u.user_id, u.user_no, u.user_name, u.position_id, d.data_id 
          FROM tb_user u 
          LEFT JOIN tb_data d ON u.user_id = d.user_id 
          WHERE u.user_username = ?";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

// ตรวจสอบว่าวันนี้ได้บันทึกเวลาเข้างานไปแล้วหรือไม่
$today = date('Y-m-d');
$check_query = "SELECT * FROM tb_timelog WHERE user_id = ? AND DATE(timelog_date) = ?";
$check_stmt = mysqli_prepare($connection, $check_query);
mysqli_stmt_bind_param($check_stmt, "is", $user_data['user_id'], $today);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);
$already_logged = mysqli_num_rows($check_result) > 0;

// ตรวจสอบการ POST ข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirmTime'])) {
        try {
            if ($already_logged) {
                echo json_encode(['status' => 'error', 'message' => 'คุณได้บันทึกเวลาเข้างานของวันนี้ไปแล้ว']);
                exit;
            }

            // รับค่าเวลาจาก form
            $current_date = date('Y-m-d H:i:s'); // วันที่และเวลาปัจจุบัน
            $current_time = date('H:i:s'); // เวลาปัจจุบัน
            $timelog_status = "เข้างาน";

            $insert_query = "INSERT INTO tb_timelog (user_id, timelog_date, timelog_in, timelog_status, data_id) 
                            VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($connection, $insert_query);
            mysqli_stmt_bind_param(
                $insert_stmt,
                "isssi",
                $user_data['user_id'],
                $current_date, // บันทึกทั้งวันที่และเวลา
                $current_time, // บันทึกเฉพาะเวลา
                $timelog_status,
                $user_data['data_id']
            );

            if (mysqli_stmt_execute($insert_stmt)) {
                // ส่งข้อมูลกลับพร้อมเวลาที่บันทึก
                echo json_encode([
                    'status' => 'success',
                    'message' => 'บันทึกเวลาเข้างานสำเร็จ',
                    'logged_time' => $current_time,
                    'logged_date' => date('d/m/Y', strtotime($current_date))
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . mysqli_error($connection)]);
            }
            exit;
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
            exit;
        }
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการบันทึกเวลาเข้างาน</title>
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
                    <span>ยืนยันการบันทึกเวลาเข้างาน</span>
                </div>

                <div class="profile-content1">
                    <div class="profile-details">
                        <div class="profile-info">
                            <div class="confirm-info">
                                <div class="info-row">
                                    <span class="info-label">รหัสพนักงาน:</span>
                                    <span><?php echo htmlspecialchars($user_data['user_no']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">ชื่อ-นามสกุล:</span>
                                    <span><?php echo htmlspecialchars($user_data['user_name']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">รหัสตำแหน่ง:</span>
                                    <span><?php echo htmlspecialchars($user_data['position_id']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">วันที่:</span>
                                    <span id="current-date"><?php echo date('d/m/Y'); ?></span>
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
                            <div class="warning">คุณได้บันทึกเวลาเข้างานของวันนี้ไปแล้ว</div>
                            <div class="button-container">
                                <a href="show_profile.php" class="confirm-button confirm-no">กลับ</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="button-container">
                            <button onclick="confirmTimeLog()" class="confirm-button confirm-yes">ยืนยัน</button>
                            <a href="show_profile.php" class="confirm-button confirm-no">ยกเลิก</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        // ฟังก์ชันอัพเดทเวลาแบบเรียลไทม์
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
        }

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alertDiv);
        }

        async function confirmTimeLog() {
            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'confirmTime=1'
                });

                const data = await response.json();
                
                if (data.status === 'success') {
                    showAlert(data.message, 'success');
                    // อัพเดทเวลาที่แสดงในหน้าเว็บ
                    document.getElementById('current-time').textContent = data.logged_time;
                    document.getElementById('current-date').textContent = data.logged_date;
                    
                    setTimeout(() => {
                        window.location.href = 'show_profile.php';
                    }, 2000);
                } else {
                    showAlert(data.message, 'danger');
                }
            } catch (error) {
                showAlert('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'danger');
                console.error('Error:', error);
            }
        }

        // เริ่มการอัพเดทเวลา
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>

</html>