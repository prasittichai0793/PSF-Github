<?php
session_start();
include '../class_conn.php';

if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$conn = new class_conn();
$connection = $conn->connect();

// Fetch user data
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $user_data['user_id'];
    $logdate = $_POST['selected_start_date'];
    $date = $_POST['selected_end_date'];
    $detail = $_POST['detail'];
    $status = "กำลังดำเนินการ";
    $doc_path = null;

    // File upload handling
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $upload_dir = "../uploads/resign_docs/";

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
                window.location.href='resign.php';
            </script>";
            exit();
        }
    }

    // Insert resignation record
    $insert_query = "INSERT INTO tb_resign (user_id, resign_logdate, resign_date, 
                    resign_detail, resign_docs, resign_status) 
                    VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($connection, $insert_query);
    mysqli_stmt_bind_param($stmt, "isssss", $user_id, $logdate, $date, $detail, $doc_path, $status);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('บันทึกข้อมูลสำเร็จ');
            window.location.href='show_history.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($connection) . "');
            window.location.href='resign.php';
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
    <title>ลาออก</title>
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

        #start_date_left {
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
                <span>ลาออก</span>
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
                                    <span class="info-label">วันที่ทำการ:</span>
                                    <input type="text" id="start_date_left" class="form-control" readonly>
                                    <input class="input-medium" type="text" id="start_date_right"
                                        data-provide="datepicker" data-date-language="th-th">
                                    <input type="hidden" name="selected_start_date" id="selected_start_date">
                                </p>
                            </div>

                            <div class="form-group">
                                <p>
                                    <span class="info-label">สิ้นสุดการทำงาน:</span>
                                    <input type="text" id="end_date_left" class="form-control" readonly>
                                </p>
                            </div>

                            <div class="form-group">
                                <p><span class="info-label">เหตุผล:</span></p>
                                <textarea name="detail" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <p><span class="info-label">แนบเอกสาร:</span></p>
                                <input type="file" name="document" class="form-control"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
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
            $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $.fn.datepicker.defaults.autoclose = true;

            $('#start_date_right').on('change', function () {
                const selectedDate = $(this).val();
                $('#start_date_left').val(selectedDate);

                // Convert date format for hidden input
                const dateParts = selectedDate.split('/');
                const formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
                $('#selected_start_date').val(formattedDate);

                // Calculate end date (15 days from start date)
                const startDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 15);

                // Format end date for display
                const endDateFormatted = endDate.getDate().toString().padStart(2, '0') + '/' +
                    (endDate.getMonth() + 1).toString().padStart(2, '0') + '/' +
                    endDate.getFullYear();
                $('#end_date_left').val(endDateFormatted);

                // Format end date for hidden input
                const formattedEndDate = `${endDate.getFullYear()}-${(endDate.getMonth() + 1).toString().padStart(2, '0')}-${endDate.getDate().toString().padStart(2, '0')}`;
                $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_end_date',
                    value: formattedEndDate
                }).appendTo('#leaveForm');
            });

            // Form validation
            $('#leaveForm').on('submit', function (e) {
                if (!$('#start_date_right').val()) {
                    e.preventDefault();
                    alert('กรุณาเลือกวันที่เริ่มต้น');
                    return false;
                }
            });
        });
    </script>
</body>

</html>