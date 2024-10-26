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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติ</title>
    <link rel="icon" href="../images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <?php include 'sidebar.php'; ?>
    <?php include 'calender.php'; ?>

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
            flex-direction: column;
            transition: left 0.3s ease;
        }

        .display-container-head {
            padding: 40px 0px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .display-container-head span {
            margin-right: 15px;
            font-size: 24px;
            font-weight: bold;
        }

        .profile-container {
            overflow: hidden;
            flex-grow: 1;
            height: auto;
            box-sizing: border-box;
        }

        .profile-container-inner {
            overflow-y: auto;
            height: 100%;
            overflow-x: hidden;
        }

        .content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .profile-content {
            background-color: #FFDEDE;
            min-width: 300px;
            width: 70%;
            max-width: 1000px;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 100px;
        }

        .title-head-profile-content {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            justify-content: center;
        }

        .profile-details {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
        }

        .profile-info {
            display: flex;
            font-size: 16px;
        }

        .profile-image {
            height: 100px;
            width: 100px;
        }

        .profile-info-topleft {
            width: 50%;
        }

        .profile-info-buttomright {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media screen and (max-width: 930px) {
            .display-container {
                position: fixed;
                top: 0px;
                left: 60px;
                right: 0px;
                bottom: 0px;
                display: flex;
                flex-direction: column;
                transition: left 0.3s ease;
            }

            .display-container-head {
                /* height: 250px; */
                padding: 20px 0px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .display-container-head span {
                font-size: 20px;
                font-weight: bold;
            }

            .title-head-profile-content {
                font-size: 16px;
            }

            .profile-content {
                padding: 10px;
            }

            .profile-details {
                margin-top: 10px;
                padding: 20px;
            }

            .profile-info {
                font-size: 16px;
                flex-direction: column-reverse;
            }

            .profile-info-topleft {
                width: 100%;
                margin-top: 20px;

            }

            .profile-info-buttomright {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="display-container">
        <div class="display-container-head">
            <span>ประวัติ</span>
        </div>

        <div class=" free-space">
        </div>

        <div class="profile-container">
            <div class="profile-container-inner">
                <div class="content">
                    <!-- Section for Personal Leave (ลากิจ) -->
                    <?php if (mysqli_num_rows($result_leave) > 0): ?>
                        <?php while ($leave_data = mysqli_fetch_assoc($result_leave)): ?>
                            <div class="profile-content">
                                <span class="title-head-profile-content">ลากิจ</span>
                                <div class="profile-details">
                                    <div class="profile-info">
                                        <div class="profile-info-topleft">
                                            <div>
                                                <p><span class="info-label">วันที่เริ่มลา:</span>
                                                    <?php echo date('d/m/Y', strtotime($leave_data['PLeave_dateStart'])); ?></p>
                                                <p><span class="info-label">วันที่สิ้นสุดลา:</span>
                                                    <?php echo date('d/m/Y', strtotime($leave_data['PLeave_dateEnd'])); ?></p>
                                                <p><span class="info-label">รายละเอียดการลา:</span>
                                                    <?php echo $leave_data['PLeave_detail']; ?></p>
                                                <p><span class="info-label">สถานะการลา:</span>
                                                    <?php echo $leave_data['PLeave_status']; ?></p>
                                                <p><span class="info-label">HR ผู้อนุมัติ:</span>
                                                    <?php echo $leave_data['hr_id']; ?>
                                                </p>
                                                <p><span class="info-label">วันที่ยื่นคำขอ:</span>
                                                    <?php echo $leave_data['PLeave_date']; ?></p>
                                            </div>
                                        </div>
                                        <div class="profile-info-buttomright">
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
                                            <img src="../images/<?php echo $image; ?>" alt="" class="profile-image">
                                            <!-- Show the appropriate image -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="profile-content">
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
                            <div class="profile-content">
                                <span class="title-head-profile-content">ลาพักร้อน</span>
                                <div class="profile-details">
                                    <div class="profile-info">
                                        <div class="profile-info-topleft">
                                            <div>
                                                <p><span class="info-label">วันที่เริ่มลา:</span>
                                                    <?php echo date('d/m/Y', strtotime($vacation_data['VLeave_dateStart'])); ?>
                                                </p>
                                                <p><span class="info-label">วันที่สิ้นสุดลา:</span>
                                                    <?php echo date('d/m/Y', strtotime($vacation_data['VLeave_dateEnd'])); ?>
                                                </p>
                                                <p><span class="info-label">รายละเอียดการลาพักร้อน:</span>
                                                    <?php echo $vacation_data['VLeave_detail']; ?></p>
                                                <p><span class="info-label">สถานะการลา:</span>
                                                    <?php echo $vacation_data['VLeave_status']; ?></p>
                                                <p><span class="info-label">HR ผู้อนุมัติ:</span>
                                                    <?php echo $vacation_data['hr_id']; ?></p>
                                                <p><span class="info-label">วันที่ยื่นคำขอ:</span>
                                                    <?php echo $vacation_data['VLeave_date']; ?></p>
                                            </div>
                                        </div>
                                        <div class="profile-info-buttomright">
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
                                            <img src="../images/<?php echo $image; ?>" alt="" class="profile-image">
                                            <!-- Show the appropriate image -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="profile-content">
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
                            <div class="profile-content">
                                <span class="title-head-profile-content">ลาออก</span>
                                <div class="profile-details">
                                    <div class="profile-info">
                                        <div class="profile-info-topleft">
                                            <div>
                                                <p><span class="info-label">วันที่ลาออก:</span>
                                                    <?php echo date('d/m/Y', strtotime($resign_data['resign_date'])); ?></p>
                                                <p><span class="info-label">รายละเอียดการลาออก:</span>
                                                    <?php echo $resign_data['resign_detail']; ?></p>
                                                <p><span class="info-label">สถานะการลาออก:</span>
                                                    <?php echo $resign_data['resign_status']; ?></p>
                                                <p><span class="info-label">HR ผู้อนุมัติ:</span>
                                                    <?php echo $resign_data['admin_id']; ?></p>
                                                <p><span class="info-label">วันที่ยื่นคำขอ:</span>
                                                    <?php echo $resign_data['resign_logdate']; ?></p>
                                            </div>
                                        </div>
                                        <div class="profile-info-buttomright">
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
                                            <img src="../images/<?php echo $image; ?>" alt="" class="profile-image">
                                            <!-- Show the appropriate image -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="profile-content">
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
                            <div class="profile-content">
                            <span class="title-head-profile-content">เอกสารที่แนบ</span>
                                <div class="profile-details">
                                    <div class="profile-info">
                                        <div class="profile-info-buttomright">
                                            <div>
                                                <p><span class="info-label">ประเภทเอกสาร:</span>
                                                    <?php echo $doc_data['docs_type']; ?></p>
                                                <p><span class="info-label">วันที่แนบเอกสาร:</span>
                                                    <?php echo date('d/m/Y', strtotime($doc_data['docs_date'])); ?></p>
                                                <p><span class="info-label">สถานะเอกสาร:</span>
                                                    <?php echo $doc_data['docs_status']; ?></p>
                                            </div>
                                        </div>
                                        <div class="profile-info-buttomright">
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
                                            <img src="../images/<?php echo $image; ?>" alt="" class="profile-image">
                                            <!-- Show the appropriate image -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="profile-content">
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
                </div>
            </div>
        </div>
    </div>


</body>

</html>