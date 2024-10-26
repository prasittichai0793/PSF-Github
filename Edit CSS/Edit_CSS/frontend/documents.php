<?php
session_start();
include '../class_conn.php';

// Check if logged in
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$conn = new class_conn();
$connection = $conn->connect();

// Fetch user data from database
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

date_default_timezone_set('Asia/Bangkok');

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $user_data['user_id'];
    $detail = $_POST['detail'];
    $status = "กำลังดำเนินการ"; // Status to be saved
    $current_datetime = date('Y-m-d H:i:s');

    // Document upload
    $doc_path = null;
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $upload_dir = "../uploads/documents_docs/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION);
        $timestamp = date('Ymd_His');
        $new_filename = $user_data['user_no'] . '_' . $timestamp . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES['document']['tmp_name'], $upload_path)) {
            $doc_path = $new_filename;
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการอัพโหลดเอกสาร'); window.location.href='documents.php';</script>";
            exit();
        }
    }

    // Insert document data
    $insert_query = "INSERT INTO tb_documents (user_id, docs_type, docs_files, docs_date, docs_status) 
                     VALUES (?, ?, ?, ?, ?)"; // Add docs_status to query

    // Get the selected document type from POST
    $docs_type = $_POST['docs_type'];

    // Prepare and bind the statement
    $stmt = mysqli_prepare($connection, $insert_query);
    mysqli_stmt_bind_param($stmt, "issss", $user_id, $docs_type, $doc_path, $current_datetime, $status); // Bind status as well

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('บันทึกข้อมูลสำเร็จ'); window.location.href='show_history.php';</script>";
        exit();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($connection) . "'); window.location.href='documents.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยื่นเอกสารเพิ่มเติม</title>
    <link rel="icon" href="../images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <?php include 'sidebar.php'; ?>
    <?php include 'calender.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

        .content,
        .info-label,
        select,
        .form-group input,
        .submit-button {
            font-size: 16px;
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
                <span>ยื่นเอกสารเพิ่มเติม</span>
            </div>

            <form method="POST" enctype="multipart/form-data" id="leaveForm">
                <div class="content">
                    <div class="profile-details">
                        <div class="profile-info">
                            <p><span class="info-label">รหัสพนักงาน:</span> <?php echo $user_data['user_no']; ?></p>
                            <p><span class="info-label">ชื่อ-นามสกุล:</span> <?php echo $user_data['user_name']; ?></p>
                            <p><span class="info-label">ตำแหน่ง:</span> <?php echo $user_data['position_name']; ?></p>
                            <p><span class="info-label">วันที่ทำการ:</span> <span
                                    id="current-date"><?php echo date('d/m/Y'); ?></span></p>

                            <div class="form-group">
                                <label for="docs_type"><span class="info-label">ประเภทเอกสาร:</span></label>
                                <select name="docs_type" id="docs_type" class="form-control" required>
                                    <option value="">เลือกประเภทเอกสาร</option>
                                    <option value="เปลี่ยน ชื่อ/นามสกุล">เปลี่ยน ชื่อ/นามสกุล</option>
                                    <option value="เปลี่ยน ที่อยู่">เปลี่ยน ที่อยู่</option>
                                    <option value="เปลี่ยน คำนำหน้า/ยศ">เปลี่ยน คำนำหน้า/ยศ</option>
                                    <option value="อื่นๆ">อื่นๆ</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <p><span class="info-label">แนบเอกสาร:</span></p>
                                <input type="file" name="document" class="form-control"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" required>
                            </div>

                            <button type="submit" class="submit-button">ยืนยันการยื่นเอกสาร</button>
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