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

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$username = $_SESSION['username'];
$query = "SELECT user_no, user_name, user_age, user_startDate, user_day, position_id 
          FROM tb_user 
          WHERE user_username = ?";

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
    <title>ลากิจ</title>
    <link rel="icon" href="../images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <?php include 'sidebar.php'; ?>

    <style>
        body {
            margin: 0;
            font-family: Verdana, sans-serif;
            background-color: #FFDEDE;
        }

        .display-container {
            position: fixed;
            top: 20px;
            left: 270px;
            right: 20px;
            bottom: 20px;
            display: flex;
            flex-direction: column;
            transition: left 0.3s ease;
        }

        .tool-for-table-container {
            background-color: #FFB8B8;
            padding: 0px 10px;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tool-for-table-container span {
            padding: 0px 20px;
            font-size: 30px;
        }

        .button-tool-for-table-container a,
        .button-tool-for-table-container button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            margin-left: 10px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }

        .free-space {
            width: 100%;
            height: 20px;
            background-color: #FFDEDE;
        }

        .table-container {
            overflow: hidden;
            background-color: #ffcece;
            flex-grow: 1;
            height: auto;
            padding: 10px;
            box-sizing: border-box;
        }

        .table-container-inner {
            overflow-y: auto;
            height: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 20px;
            text-align: left;
            white-space: nowrap;
            position: relative;
        }

        th {
            background-color: #FFB8B8;
            color: white;
        }

        thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #FFB8B8;
        }

        tbody tr:nth-child(odd) {
            background-color: #FFDEDE;
        }

        tbody tr:nth-child(even) {
            background-color: #ffcece;
        }

        td::before,
        th::before {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            width: 1px;
            height: 80%;
            background-color: black;
            transform: translateY(-50%);
        }

        td:last-child::before,
        th:last-child::before {
            display: none;
        }

        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #888;
            border: 2px solid #f1f1f1;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        ::-webkit-scrollbar-horizontal {
            height: 12px;
        }

        ::-webkit-scrollbar-track-horizontal {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb-horizontal {
            background-color: #888;
            border-radius: 10px;
            border: 2px solid #f1f1f1;
        }

        .profile-details {
            background-color: #ffffff;
        }

        @media screen and (max-width: 930px) {
            .display-container {
                position: fixed;
                background-color: #ff00bf;
                top: 10px;
                left: 70px;
                right: 10px;
                bottom: 10px;
                display: flex;
                flex-direction: column;
            }

            .tool-for-table-container {
                background-color: #FFB8B8;
                padding: 0px 10px;
                height: 50px;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            .button-tool-for-table-container {
                margin-left: auto;
                display: flex;
                align-items: center;
            }

            .tool-for-table-container span {
                padding: 0px 15px;
                font-size: 20px;
            }

            .button-tool-for-table-container a,
            .button-tool-for-table-container button {
                background-color: #3498db;
                color: white;
                border: none;
                padding: 10px 15px;
                margin-left: 10px;
                font-size: 12px;
                cursor: pointer;
                border-radius: 5px;
                text-decoration: none;
            }

            .table-container {
                overflow: hidden;
                background-color: #ffcece;
                flex-grow: 1;
                height: auto;
                padding: 10px;
                box-sizing: border-box;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="display-container">
        <div class="tool-for-table-container">
            <span>ลากิจ</span>
        </div>

        <div class="table-container">
            <div class="profile-details">
                <div class="profile-info">
                    <p><span class="info-label">รหัสพนักงาน:</span> <?php echo $user_data['user_no']; ?></p>
                    <p><span class="info-label">ชื่อ-นามสกุล:</span> <?php echo $user_data['user_name']; ?></p>
                    <p><span class="info-label">รหัสตำแหน่ง:</span> <?php echo $user_data['position_id']; ?></p>
                    <p><span class="info-label">เริ่มวันที่:</span> <span id="current-date"></span></p>
                    <p><span class="info-label">สิ้นสุดวันที่:</span> <span id="current-date"></span></p>
                    <p><span class="info-label">เหตุผล:</span> </p>
                    <?php include 'calender.php'; ?>
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
        }

        // อัพเดทเวลาทุกๆ 1 วินาที
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>

</html>