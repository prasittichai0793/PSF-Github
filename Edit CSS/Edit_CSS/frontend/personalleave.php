<?php
session_start();
include '../class_conn.php';

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$conn = new class_conn();
$connection = $conn->connect();

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$username = $_SESSION['username'];
$query = "SELECT u.user_id, u.user_no, u.user_name, u.user_age, u.user_startDate, u.user_day, 
          p.position_name 
          FROM tb_user u
          LEFT JOIN tb_position p ON u.position_id = p.position_id
          WHERE u.user_username = ?";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

// การจัดการการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $user_data['user_id'];
    $dateStart = $_POST['selected_start_date'];
    $dateEnd = $_POST['selected_end_date'];
    $detail = $_POST['detail'];
    $status = "กำลังดำเนินการ";
    $current_datetime = date('Y-m-d');

    $doc_path = null;

    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $upload_dir = "../uploads/personalleave_docs/";

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // ดึงนามสกุลไฟล์จากไฟล์ที่ผู้ใช้เลือก
        $file_extension = pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION);

        // สร้างชื่อไฟล์ใหม่โดยใช้ user_no และ current_datetime พร้อมนามสกุลไฟล์เดิม
        $timestamp = date('Ymd_His'); // รูปแบบ: YYYYMMDD_HHMMSS
        $new_filename = $user_data['user_no'] . '_' . $timestamp . '.' . $file_extension; // ชื่อไฟล์ใหม่
        $upload_path = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES['document']['tmp_name'], $upload_path)) {
            $doc_path = $new_filename; // เก็บชื่อไฟล์ใหม่ในตัวแปร $doc_path
        } else {
            echo "<script>
        alert('เกิดข้อผิดพลาดในการอัพโหลดเอกสาร');
        window.location.href='personalleave.php';
    </script>";
            exit();
        }

    }


    // ดำเนินการบันทึกข้อมูล
    $insert_query = "INSERT INTO tb_personalleave (user_id, PLeave_dateStart, PLeave_dateEnd, 
        PLeave_detail, PLeave_docs, PLeave_status, PLeave_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($connection, $insert_query);
    mysqli_stmt_bind_param($stmt, "issssss", $user_id, $dateStart, $dateEnd, $detail, $doc_path, $status, $current_datetime);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('บันทึกข้อมูลสำเร็จ');
            window.location.href='show_history.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($connection) . "');
            window.location.href='personalleave.php';
        </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลากิจ</title>
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
            align-items: center;
            justify-content: center;
            flex-direction: column;
            transition: left 0.3s ease;
        }

        .display-content {
            background-color: #FFDEDE;
            min-width: 290px;
            width: 70%;
            max-width: 900px;
            height: auto;
            border-radius: 30px;
        }

        .display-container-head span {
            padding-top: 20px;
            font-size: 24px;
            font-weight: bold;
            display: flex;
            justify-content: center;
        }

        .content {
            margin: 20px;
            padding: 20px;
            border-radius: 20px;
            background-color: #ffffff;
            display: flex;
            align-items: center;
        }

        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .submit-button:hover {
            background-color: #45a049;
        }

        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
        }

        #start_date_left,
        #end_date_left {
            display: none;
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
            }

            .display-content {
                background-color: #FFDEDE;
                height: auto;
                border-radius: 15px;
                margin: 10px;
            }

            .display-container-head span {
                padding-top: 10px;
                font-size: 20px;
                font-weight: bold;
                display: flex;
                justify-content: center;
            }

            .content {
                margin: 10px;
                padding: 20px;
                border-radius: 10px;
                font-size: 14px;
                background-color: #ffffff;
            }

            .form-group {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            .form-group input {
                width: 100%;
                box-sizing: border-box;
            }

            .info-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="display-container">
        <div class="display-content">
            <div class="display-container-head">
                <span>ลากิจ</span>
            </div>

            <form method="POST" enctype="multipart/form-data" id="leaveForm">
                <div class="content">
                    <div class="profile-details">
                        <div class="profile-info">
                            <p><span class="info-label">รหัสพนักงาน:</span> <?php echo $user_data['user_no']; ?></p>
                            <p><span class="info-label">ชื่อ-นามสกุล:</span> <?php echo $user_data['user_name']; ?></p>
                            <p><span class="info-label">ตำแหน่ง:</span> <?php echo $user_data['position_name']; ?></p>
                            <div class="form-group">
                                <p>
                                    <span class="info-label">เริ่มวันที่:</span>
                                    <input type="text" id="start_date_left" class="form-control" readonly autocomplete="off">
                                    <input class="input-medium" type="text" id="start_date_right"
                                        data-provide="datepicker" data-date-language="th-th" autocomplete="off">
                                    <input type="hidden" name="selected_start_date" id="selected_start_date" autocomplete="off">
                                </p>
                            </div>

                            <div class="form-group">
                                <p>
                                    <span class="info-label">สิ้นสุดวันที่:</span>
                                    <input type="text" id="end_date_left" class="form-control" readonly autocomplete="off">
                                    <input class="input-medium" type="text" id="end_date_right"
                                        data-provide="datepicker" data-date-language="th-th" autocomplete="off">
                                    <input type="hidden" name="selected_end_date" id="selected_end_date" autocomplete="off">
                                </p>
                            </div>

                            <div class="form-group">
                                <p><span class="info-label">เหตุผล:</span></p>
                                <textarea name="detail" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <p><span class="info-label">แนบเอกสาร:</span></p>
                                <input type="file" name="document" class="form-control"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" autocomplete="off">
                            </div>


                            <button type="submit" class="submit-button">ยืนยันการลา</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // ตั้งค่า datepicker
            $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $.fn.datepicker.defaults.autoclose = true;

            // จัดการวันที่เริ่มต้น
            $('#start_date_right').on('change', function () {
                const selectedDate = $(this).val();
                $('#start_date_left').val(selectedDate);

                // แปลงรูปแบบวันที่สำหรับ hidden input (yyyy-mm-dd)
                const dateParts = selectedDate.split('/');
                const formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
                $('#selected_start_date').val(formattedDate);
            });

            // จัดการวันที่สิ้นสุด
            $('#end_date_right').on('change', function () {
                const selectedDate = $(this).val();
                $('#end_date_left').val(selectedDate);

                // แปลงรูปแบบวันที่สำหรับ hidden input (yyyy-mm-dd)
                const dateParts = selectedDate.split('/');
                const formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
                $('#selected_end_date').val(formattedDate);
            });

            // เพิ่มการตรวจสอบก่อนส่งฟอร์ม
            $('#leaveForm').on('submit', function (e) {
                if (!$('#selected_start_date').val() || !$('#selected_end_date').val()) {
                    e.preventDefault();
                    alert('กรุณาเลือกวันที่เริ่มต้นและวันที่สิ้นสุด');
                    return false;
                }

                const startDate = new Date($('#selected_start_date').val());
                const endDate = new Date($('#selected_end_date').val());

                if (endDate < startDate) {
                    e.preventDefault();
                    alert('วันที่สิ้นสุดต้องไม่น้อยกว่าวันที่เริ่มต้น');
                    return false;
                }
            });
        });
    </script>
</body>

</html>