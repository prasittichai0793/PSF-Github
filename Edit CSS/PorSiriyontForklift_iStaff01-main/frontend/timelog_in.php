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
            mysqli_stmt_bind_param($insert_stmt, "isssi", 
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
    <style>
        .confirm-container {
            background-color: #FFDEDE;
            padding: 20px;
            border-radius: 20px;
            max-width: 800px;
            margin: 40px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .confirm-info {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .info-row {
            margin: 10px 0;
            display: flex;
            align-items: center;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #333;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .confirm-button {
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            text-decoration: none;
            color: white;
        }
        .confirm-yes {
            background-color: #4CAF50;
        }
        .confirm-no {
            background-color: #ff4444;
        }
        .warning {
            color: #ff4444;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
        .alert {
            padding: 15px;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 5px;
            text-align: center;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div id="alertContainer"></div>

    <div class="confirm-container">
        <h2 style="text-align: center;">ยืนยันการบันทึกเวลาเข้างาน</h2>
        
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

        <?php if ($already_logged): ?>
            <div class="warning">คุณได้บันทึกเวลาเข้างานของวันนี้ไปแล้ว</div>
            <div class="button-container">
                <a href="show_profile.php" class="confirm-button confirm-no">กลับ</a>
            </div>
        <?php else: ?>
            <div class="button-container">
                <button onclick="confirmTimeLog()" class="confirm-button confirm-yes">ยืนยัน</button>
                <a href="show_profile.php" class="confirm-button confirm-no">ยกเลิก</a>
            </div>
        <?php endif; ?>
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