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

<?php include 'sidebar.php'; ?>
<?php include 'calender.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลาออก</title>
    <style>
        .profile-container {
            display: flex;
            align-items: flex-start;
            background-color: #FFDEDE;
            padding: 20px;
            border-radius: 20px;
            max-width: 800px;
            margin: 30px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-container2 {
            display: flex;
            align-items: flex-start;
            background-color: #FFDEDE;
            padding: 20px;
            border-radius: 20px;
            max-width: 800px;
            margin: 40px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-image {
            border-radius: 20px;
            overflow: hidden;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .profile-details {
            flex-grow: 1;
        }

        .profile-details h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .timelog_in {
            flex-grow: 1;
        }

        .timelog_in h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .profile-info {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .profile-info p {
            margin: 10px 0;
            color: #555;
        }

        .timelog_in-info {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        .timelog_in-info p {
            margin: 10px 0;
            color: #555;
        }

        .info-label {
            font-weight: bold;
            color: #333;
            width: 120px;
            display: inline-block;
        }

        .info-label2 {
            font-weight: bold;
            color: #333;
            width: 180px;
            display: inline-block;
        }

        #current-time {
            font-size: 2em;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .time-log-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .time-log-button:hover {
            background-color: #45a049;
        }

        .timelog_in-info {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            text-align: center;
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
        }

        .time-button-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 10px;
        }

        .time-log-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            text-decoration: none;
            transition: background-color 0.3s;
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
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            margin-top: 20px;
        }

        .submit-button:hover {
            background-color: #45a049;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .date-display {
            display: inline-block;
            padding: 5px 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-left: 10px;
        }

        .form-control[readonly] {
            background-color: #fff;
            width: 200px;
            display: inline-block;
            margin-right: 10px;
        }

        .input-medium {
            width: 150px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <div class="profile-details">
            <h2>ลาออก</h2>
            <form method="POST" enctype="multipart/form-data" id="leaveForm">
                <div class="profile-info">
                    <p><span class="info-label">รหัสพนักงาน:</span> <?php echo $user_data['user_no']; ?></p>
                    <p><span class="info-label">ชื่อ-นามสกุล:</span> <?php echo $user_data['user_name']; ?></p>
                    <p><span class="info-label">ตำแหน่ง:</span> <?php echo $user_data['position_name']; ?></p>
                    <div class="form-group">
                        <p>
                            <span class="info-label">วันที่ทำการ:</span>
                            <input type="text" id="start_date_left" class="form-control" readonly>
                            <input class="input-medium" type="text" id="start_date_right" data-provide="datepicker"
                                data-date-language="th-th">
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

        // Get current time and timezone
        const currentDate = new Date();
        const time = currentDate.toTimeString().split(' ')[0]; // Get time in HH:MM:SS format
        const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone; // Get timezone

        // Convert date format for hidden input
        const dateParts = selectedDate.split('/');
        const formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]} ${time} (${timeZone})`;
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
    $('#leaveForm').on('submit', function(e) {
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