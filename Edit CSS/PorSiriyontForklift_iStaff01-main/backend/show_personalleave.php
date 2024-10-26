<?php
session_start(); // เริ่ม session
include 'sidebar.php';
include '../class_conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แจ้งลากิจ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/position_1.css">
    <script src="../print.js" defer></script>
    <style>
        .btn-approve {
            background-color: green;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-reject {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .status-green {
            background-color: lightgreen;
            padding: 5px;
            border-radius: 4px;
        }
        .status-red {
            background-color: lightcoral;
            padding: 5px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>แจ้งลากิจ</h1>
                <div class="actions">
                    <button class="btn btn-save" onclick="printPage()"><i class="fa fa-save"></i> พิมพ์</button>
                </div>
            </header>
            <div class="content">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">user</th>
                            <th scope="col">เริ่มวันที่</th>
                            <th scope="col">สิ้นสุดวันที่</th>
                            <th scope="col">เหตุผล</th>
                            <th scope="col">เอกสาร</th>
                            <th scope="col">เวลาทำการ</th>
                            <th scope="col">สถานะ</th>
                            <th scope="col">HR ที่อนุมัติ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // สร้าง object class_conn
                        $db = new class_conn();
                        $conn = $db->connect();

                        // ตรวจสอบการเชื่อมต่อ
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // ดึงข้อมูลจาก tb_personalleave
                        $sql = "SELECT * FROM tb_personalleave";
                        $result = $conn->query($sql);

                        // ตรวจสอบว่ามีข้อมูลหรือไม่
                        if ($result->num_rows > 0) {
                            // วนลูปแสดงผลข้อมูล
                            while ($row = $result->fetch_assoc()) {
                                $statusClass = '';
                                if ($row['PLeave_status'] == 'อนุมัติ') {
                                    $statusClass = 'status-green';
                                } elseif ($row['PLeave_status'] == 'ไม่อนุมัติ') {
                                    $statusClass = 'status-red';
                                }

                                echo "<tr>
                                <td>{$row['PLeave_id']}</td>
                                <td>{$row['user_id']}</td>
                                <td>{$row['PLeave_dateStart']}</td>
                                <td>{$row['PLeave_dateEnd']}</td>
                                <td>{$row['PLeave_detail']}</td>
                                <td>{$row['PLeave_docs']}</td>
                                <td>{$row['PLeave_date']}</td>
                                <td class='{$statusClass}'>{$row['PLeave_status']}</td>";

                                // แสดง HR ID ที่อนุมัติ/ไม่อนุมัติ หรือแสดงปุ่ม
                                if ($row['PLeave_status'] == 'อนุมัติ' || $row['PLeave_status'] == 'ไม่อนุมัติ') {
                                    echo "<td>{$row['hr_id']}</td>";
                                } else {
                                    echo "<td>
                                        <form method='post' action=''>
                                            <input type='hidden' name='pleave_id' value='{$row['PLeave_id']}'>
                                            <button class='btn-approve' name='approve'>อนุมัติ</button>
                                            <button class='btn-reject' name='reject'>ไม่อนุมัติ</button>
                                        </form>
                                    </td>";
                                }

                                echo "</tr>";
                            }
                        }

                        // ปิดการเชื่อมต่อ
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// ตรวจสอบการ submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pleave_id = $_POST['pleave_id'];
    $hr_id = $_SESSION['hr_id']; // ดึง hr_id จาก session

    // สร้าง object class_conn
    $db = new class_conn();
    $conn = $db->connect();

    if (isset($_POST['approve'])) {
        // ดึงข้อมูลวันที่เริ่มและสิ้นสุดการลา
        $sql = "SELECT PLeave_dateStart, PLeave_dateEnd, user_id FROM tb_personalleave WHERE PLeave_id='$pleave_id'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $pleave_dateStart = $row['PLeave_dateStart'];
            $pleave_dateEnd = $row['PLeave_dateEnd'];
            $user_id = $row['user_id'];

            // คำนวณจำนวนวันที่ลา
            $dateStart = new DateTime($pleave_dateStart);
            $dateEnd = new DateTime($pleave_dateEnd);
            $interval = $dateStart->diff($dateEnd);
            $daysLeave = $interval->days + 1; // รวมวันเริ่มและสิ้นสุด

            // ดึงจำนวนวันลาที่เหลือของผู้ใช้
            $sqlUser = "SELECT user_day FROM tb_user WHERE user_id='$user_id'";
            $resultUser = $conn->query($sqlUser);
            
            if ($resultUser->num_rows > 0) {
                $userRow = $resultUser->fetch_assoc();
                $userDays = $userRow['user_day'];

                // หักลบวันลาจาก user_day
                $updatedUserDays = $userDays - $daysLeave;

                // อัปเดตจำนวนวันลาของผู้ใช้ใน tb_user
                $sqlUpdateUser = "UPDATE tb_user SET user_day='$updatedUserDays' WHERE user_id='$user_id'";
                $conn->query($sqlUpdateUser);
            }

            // อัปเดตสถานะการลาเป็น 'อนุมัติ'
            $sqlUpdate = "UPDATE tb_personalleave SET PLeave_status='อนุมัติ', hr_id='$hr_id' WHERE PLeave_id='$pleave_id'";
        }
    } elseif (isset($_POST['reject'])) {
        // อัปเดตสถานะการลาเป็น 'ไม่อนุมัติ' แต่ไม่ลดวันลา
        $sqlUpdate = "UPDATE tb_personalleave SET PLeave_status='ไม่อนุมัติ', hr_id='$hr_id' WHERE PLeave_id='$pleave_id'";
    }

    // ดำเนินการอัปเดตสถานะ
    if ($conn->query($sqlUpdate) === TRUE) {
        echo "<script>alert('สถานะได้รับการปรับปรุงแล้ว'); window.location.href = window.location.href;</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    // ปิดการเชื่อมต่อ
    $conn->close();
}
?>
