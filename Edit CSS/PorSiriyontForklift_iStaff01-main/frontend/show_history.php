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

// ดึงข้อมูลการลากิจ (PLeave) ของผู้ใช้ที่ล็อกอิน
$username = $_SESSION['username'];
$query_leave = "SELECT pl.PLeave_dateStart, pl.PLeave_dateEnd, pl.PLeave_detail, pl.PLeave_status, pl.hr_id, pl.PLeave_date
                FROM tb_personalleave pl 
                JOIN tb_user u ON pl.user_id = u.user_id
                WHERE u.user_username = ?";

$stmt_leave = mysqli_prepare($connection, $query_leave);
mysqli_stmt_bind_param($stmt_leave, "s", $username);
mysqli_stmt_execute($stmt_leave);
$result_leave = mysqli_stmt_get_result($stmt_leave);

// ดึงข้อมูลลาพักร้อน (Vacation Leave) ของผู้ใช้ที่ล็อกอิน
$query_vacation = "SELECT vl.VLeave_dateStart, vl.VLeave_dateEnd, vl.VLeave_detail, vl.VLeave_status, vl.hr_id, vl.VLeave_date
                   FROM tb_vacationleave vl 
                   JOIN tb_user u ON vl.user_id = u.user_id
                   WHERE u.user_username = ?";

$stmt_vacation = mysqli_prepare($connection, $query_vacation);
mysqli_stmt_bind_param($stmt_vacation, "s", $username);
mysqli_stmt_execute($stmt_vacation);
$result_vacation = mysqli_stmt_get_result($stmt_vacation);

// ดึงข้อมูลลาออก (Resignation) ของผู้ใช้ที่ล็อกอิน
$query_resign = "SELECT r.resign_logdate, r.resign_detail, r.resign_date, r.resign_status, r.admin_id
                 FROM tb_resign r 
                 JOIN tb_user u ON r.user_id = u.user_id
                 WHERE u.user_username = ?";

$stmt_resign = mysqli_prepare($connection, $query_resign);
mysqli_stmt_bind_param($stmt_resign, "s", $username);
mysqli_stmt_execute($stmt_resign);
$result_resign = mysqli_stmt_get_result($stmt_resign);

// ดึงข้อมูลเอกสารเพิ่มเติม (Documents)
$query_documents = "SELECT d.docs_type, d.docs_date, d.hr_id, d.docs_status 
                    FROM tb_documents d 
                    JOIN tb_user u ON d.user_id = u.user_id
                    WHERE u.user_username = ?";
$stmt_documents = mysqli_prepare($connection, $query_documents);
mysqli_stmt_bind_param($stmt_documents, "s", $username);
mysqli_stmt_execute($stmt_documents);
$result_documents = mysqli_stmt_get_result($stmt_documents);
?>

<?php include 'sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติ</title>
    <style>
        .profile-container {
            display: flex;
            align-items: flex-start;
            background-color: #FFDEDE;
            padding: 20px;
            border-radius: 20px;
            max-width: 800px;
            margin: 18px auto;
            margin-left: 500px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-info {
            background-color: white;
            padding: 15px 55px;
            border-radius: 10px;
            width: 690px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-info p {
            margin: 10px 0;
            color: #555;
        }

        .info-label {
            font-weight: bold;
            color: #333;
            width: 200px;
            display: inline-block;
        }

        .profile-image {
            margin-left: 20px;
            max-width: 100px;
        }
    </style>
</head>

<body>
    <h2 style="text-align:center; margin-left: 160px;">ประวัติ</h2>

    <!-- Section for Personal Leave (ลากิจ) -->
    <?php if (mysqli_num_rows($result_leave) > 0): ?>
        <?php while ($leave_data = mysqli_fetch_assoc($result_leave)): ?>
            <div class="profile-container">
                <div class="profile-details">
                    <div class="profile-info">
                        <div>
                            <h3 style="text-align:center;">ลากิจ</h3>
                            <p><span class="info-label">วันที่เริ่มลา:</span>
                                <?php echo date('d/m/Y', strtotime($leave_data['PLeave_dateStart'])); ?></p>
                            <p><span class="info-label">วันที่สิ้นสุดลา:</span>
                                <?php echo date('d/m/Y', strtotime($leave_data['PLeave_dateEnd'])); ?></p>
                            <p><span class="info-label">รายละเอียดการลา:</span> <?php echo $leave_data['PLeave_detail']; ?></p>
                            <p><span class="info-label">สถานะการลา:</span> <?php echo $leave_data['PLeave_status']; ?></p>
                            <p><span class="info-label">HR ผู้อนุมัติ:</span> <?php echo $leave_data['hr_id']; ?></p>
                            <p><span class="info-label">วันที่ยื่นคำขอ:</span> <?php echo $leave_data['PLeave_date']; ?></p>
                        </div>
                        <?php 
                        // ตรวจสอบสถานะการลาเพื่อแสดงภาพที่เหมาะสม
                        $image = '';
                        if ($leave_data['PLeave_status'] === 'อนุมัติ') {
                            $image = '2.png'; // Approved
                        } elseif ($leave_data['PLeave_status'] === 'ไม่อนุมัติ') {
                            $image = '1.png'; // Not approved
                        } else {
                            $image = '4.png'; // In progress
                        }
                        ?>
                        <img src="../images/<?php echo $image; ?>" alt="" class="profile-image"> <!-- Show the appropriate image -->
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="profile-container">
            <div class="profile-details">
                <div class="profile-info">
                    <p>ไม่มีข้อมูลการลากิจในขณะนี้</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Section for Vacation Leave (ลาพักร้อน) -->
    <?php if (mysqli_num_rows($result_vacation) > 0): ?>
        <?php while ($vacation_data = mysqli_fetch_assoc($result_vacation)): ?>
            <div class="profile-container">
                <div class="profile-details">
                    <div class="profile-info">
                        <div>
                            <h3 style="text-align:center;">ลาพักร้อน</h3>
                            <p><span class="info-label">วันที่เริ่มลา:</span>
                                <?php echo date('d/m/Y', strtotime($vacation_data['VLeave_dateStart'])); ?></p>
                            <p><span class="info-label">วันที่สิ้นสุดลา:</span>
                                <?php echo date('d/m/Y', strtotime($vacation_data['VLeave_dateEnd'])); ?></p>
                            <p><span class="info-label">รายละเอียดการลาพักร้อน:</span>
                                <?php echo $vacation_data['VLeave_detail']; ?></p>
                            <p><span class="info-label">สถานะการลา:</span> <?php echo $vacation_data['VLeave_status']; ?></p>
                            <p><span class="info-label">HR ผู้อนุมัติ:</span> <?php echo $vacation_data['hr_id']; ?></p>
                            <p><span class="info-label">วันที่ยื่นคำขอ:</span> <?php echo $vacation_data['VLeave_date']; ?></p>
                        </div>
                        <?php 
                        // ตรวจสอบสถานะการลาพักร้อนเพื่อแสดงภาพที่เหมาะสม
                        $image = '';
                        if ($vacation_data['VLeave_status'] === 'อนุมัติ') {
                            $image = '2.png'; // Approved
                        } elseif ($vacation_data['VLeave_status'] === 'ไม่อนุมัติ') {
                            $image = '1.png'; // Not approved
                        } else {
                            $image = '4.png'; // In progress
                        }
                        ?>
                        <img src="../images/<?php echo $image; ?>" alt="" class="profile-image"> <!-- Show the appropriate image -->
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="profile-container">
            <div class="profile-details">
                <div class="profile-info">
                    <p>ไม่มีข้อมูลการลาพักร้อนในขณะนี้</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Section for Resignation (ลาออก) -->
    <?php if (mysqli_num_rows($result_resign) > 0): ?>
        <?php while ($resign_data = mysqli_fetch_assoc($result_resign)): ?>
            <div class="profile-container">
                <div class="profile-details">
                    <div class="profile-info">
                        <div>
                            <h3 style="text-align:center;">ลาออก</h3>
                            <p><span class="info-label">วันที่ลาออก:</span> 
                                <?php echo date('d/m/Y', strtotime($resign_data['resign_date'])); ?></p>
                            <p><span class="info-label">รายละเอียดการลาออก:</span>
                                <?php echo $resign_data['resign_detail']; ?></p>
                            <p><span class="info-label">สถานะการลาออก:</span> <?php echo $resign_data['resign_status']; ?></p>
                            <p><span class="info-label">HR ผู้อนุมัติ:</span> <?php echo $resign_data['admin_id']; ?></p>
                            <p><span class="info-label">วันที่ยื่นคำขอ:</span> <?php echo $resign_data['resign_logdate']; ?></p>
                        </div>
                        <?php 
                        // ตรวจสอบสถานะการลาออกเพื่อแสดงภาพที่เหมาะสม
                        $image = '';
                        if ($resign_data['resign_status'] === 'อนุมัติ') {
                            $image = '2.png'; // Approved
                        } elseif ($resign_data['resign_status'] === 'ไม่อนุมัติ') {
                            $image = '1.png'; // Not approved
                        } else {
                            $image = '4.png'; // In progress
                        }
                        ?>
                        <img src="../images/<?php echo $image; ?>" alt="" class="profile-image"> <!-- Show the appropriate image -->
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="profile-container">
            <div class="profile-details">
                <div class="profile-info">
                    <p>ไม่มีข้อมูลการลาออกในขณะนี้</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Section for Documents (เอกสาร) -->
<?php if (mysqli_num_rows($result_documents) > 0): ?>
    <?php while ($doc_data = mysqli_fetch_assoc($result_documents)): ?>
        <div class="profile-container">
            <div class="profile-details">
                <div class="profile-info">
                    <div>
                        <h3 style="text-align:center;">เอกสารที่แนบ</h3>
                        <p><span class="info-label">ประเภทเอกสาร:</span> <?php echo $doc_data['docs_type']; ?></p>
                        <p><span class="info-label">วันที่แนบเอกสาร:</span> <?php echo date('d/m/Y', strtotime($doc_data['docs_date'])); ?></p>
                        <p><span class="info-label">สถานะเอกสาร:</span> <?php echo $doc_data['docs_status']; ?></p>
                    </div>
                    <?php 
                    // ตรวจสอบสถานะเอกสารเพื่อแสดงภาพที่เหมาะสม
                    $image = '';
                    if ($doc_data['docs_status'] === 'อนุมัติ') {
                        $image = '2.png'; // Approved
                    } elseif ($doc_data['docs_status'] === 'ไม่อนุมัติ') {
                        $image = '1.png'; // Not approved
                    } else {
                        $image = '4.png'; // In progress
                    }
                    ?>
                    <img src="../images/<?php echo $image; ?>" alt="" class="profile-image"> <!-- Show the appropriate image -->
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="profile-container">
        <div class="profile-details">
            <div class="profile-info">
                <p>ไม่มีประวัติการยื่นเอกสารเพิ่มเติม</p>
            </div>
        </div>
    </div>
<?php endif; ?>

    <?php
    // ปิดการเชื่อมต่อฐานข้อมูล
    mysqli_close($connection);
    ?>
</body>
</html>
