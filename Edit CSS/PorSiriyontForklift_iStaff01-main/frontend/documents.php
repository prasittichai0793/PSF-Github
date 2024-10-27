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

<?php include 'sidebar.php'; ?>
<?php include 'calender.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยื่นเอกสารเพิ่มเติม</title>
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
        .form-control {
        width: 100%;
        height: 30px;
        font-size: 13px;
    }


    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="path/to/your/datepicker.css"> <!-- Make sure to include your datepicker CSS -->
    <script src="path/to/your/datepicker.js"></script> <!-- Make sure to include your datepicker JS -->
</head>

<body>
    <div class="profile-container">
        <div class="profile-details">
            <h2>ยื่นเอกสารเพิ่มเติม</h2>
            <form method="POST" enctype="multipart/form-data" id="leaveForm">
                <div class="profile-info">
                    <p><span class="info-label">รหัสพนักงาน:</span> <?php echo $user_data['user_no']; ?></p>
                    <p><span class="info-label">ชื่อ-นามสกุล:</span> <?php echo $user_data['user_name']; ?></p>
                    <p><span class="info-label">ตำแหน่ง:</span> <?php echo $user_data['position_name']; ?></p>
                    <p><span class="info-label">วันที่ทำการ:</span> <span id="current-date"><?php echo date('d/m/Y'); ?></span></p>
                    
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
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Set up datepicker
            $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $.fn.datepicker.defaults.autoclose = true;

            // Handle form submission
            $('#leaveForm').on('submit', function (e) {
                const docsType = $('#docs_type').val();
                if (!docsType) {
                    e.preventDefault();
                    alert('กรุณาเลือกประเภทเอกสาร');
                    return false;
                }

                // Additional validation can be done here
            });
        });
    </script>
</body>
</html>
