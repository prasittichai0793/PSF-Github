<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F เพิ่มข้อมูลพนักงานแม่บ้าน</title>

    <link rel="icon" href="../images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <script src="JavaScript/calculate.js"></script>

    <?php include '../class_conn.php'; ?>
    <?php include 'calender.php'; ?>

    <style>
        body {
            margin: 0;
            font-family: Verdana, sans-serif;
        }

        .display-container {
            width: 100%;
            height: 100vh;
        }

        .container-content {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            width: 60%;
            max-width: 700px;
        }

        .content span {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content label {
            font-size: 15px;
        }

        .content input,
        .content select {
            font-size: 14px;
            width: 100%;
            height: auto;
            box-sizing: border-box;
        }

        .content button {
            font-size: 14px;
            color: #fff;
            padding: 10px 15px;
            margin-top: 10px;
            border: none;
        }

        .btn-save {
            background: #259b24;
        }

        .btn-save:hover {
            background: #056f00;
            color: #fff;
        }

        .btn-cancel {
            background: #e51c23;
        }

        .btn-cancel:hover {
            background: #b0120a;
            color: #fff;
        }

        .fa-save,
        .fa-times {
            margin-right: 5px;
        }

        .user_exp {
            pointer-events: none;
        }

        @media screen and (max-width: 930px) {
            .content {
                min-width: 150px;
            }

            .content button {
                font-size: 12px;
                color: #fff;
                padding: 5px 10px;
                margin-top: 10px;
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="display-container">
        <div class="display-content">
            <div class="container-content">
                <div class="content">
                    <span>F เพิ่มข้อมูลพนักงานแม่บ้าน</span>
                    <form action="add_pos6.php" method="post">
                        <div class="form-group">
                            <label for="user_name">ชื่อ:</label>
                            <input type="text" id="user_name" name="user_name" required autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label class="info-label">เริ่มวันที่:</label>
                            <input class="input-medium" type="text" id="user_startDate" name="user_startDate"
                                data-provide="datepicker" data-date-language="th-th" autocomplete="off"
                                onchange="calculateExperienceUserStartDate()" onkeydown="return false;"
                                onpaste="return false;">
                        </div>

                        <div class="form-group user_exp">
                            <label for="user_exp">อายุงาน:</label>
                            <input type="text" id="user_exp" name="user_exp" readonly autocomplete="off">
                        </div>

                        <div class="form-group user_exp">
                            <label for="user_leaveDays">วันลา:</label>
                            <input type="text" id="user_leaveDays" name="user_leaveDays" readonly autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="user_idNumber">เลขบัตรประชาชน:</label>
                            <input type="text" id="user_idNumber" name="user_idNumber" required maxlength="13"
                                pattern="\d{13}" title="กรุณากรอกเลขบัตรประชาชน 13 ตัวเท่านั้น" autocomplete="off"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                        </div>

                        <div class="form-group">
                            <label for="user_phoneNumber">เบอร์โทรศัพท์:</label>
                            <input type="text" id="user_phoneNumber" name="user_phoneNumber" required maxlength="10"
                                pattern="\d{10}" title="กรุณากรอกเบอร์โทรศัพท์ 10 ตัวเท่านั้น" autocomplete="off"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                        </div>

                        <div class="form-group">
                            <label class="info-label">วัน/เดือน/ปี เกิด:</label>
                            <input class="input-medium" type="text" id="user_birthDate" name="user_birthDate"
                                data-provide="datepicker" data-date-language="th-th" autocomplete="off"
                                onchange="calculateExperienceUserBirthDate()" onkeydown="return false;"
                                onpaste="return false;">
                        </div>

                        <div class="form-group user_exp">
                            <label for="user_age">อายุ:</label>
                            <input type="text" id="user_age" name="user_age" readonly autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="user_username">Username:</label>
                            <input type="text" id="user_username" name="user_username" required autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="user_password">Password:</label>
                            <input type="password" id="user_password" name="user_password" required autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="user_gender">เพศ:</label>
                            <select id="user_gender" name="user_gender" required>
                                <option value="" disabled selected>กรุณาเลือกเพศ</option>
                                <option value="male">ชาย</option>
                                <option value="female">หญิง</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-save"><i class="fa fa-save"></i> บันทึก</button>
                        <button type="button" class="btn btn-cancel" onclick="location.href='show_position6.php'"><i class="fas fa-times"></i>ยกเลิก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
// ตรวจสอบว่าฟอร์มถูกส่งหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name']; // รับค่า user_name จากฟอร์ม
    $user_startDate = $_POST['user_startDate']; // รับค่า user_startDate จากฟอร์ม
    $user_exp = $_POST['user_exp']; // รับค่า user_exp จากฟอร์ม
    $user_idNumber = $_POST['user_idNumber']; // รับค่า user_idNumber จากฟอร์ม
    $user_phoneNumber = $_POST['user_phoneNumber']; // รับค่า user_phoneNumber จากฟอร์ม
    $user_birthDate = $_POST['user_birthDate']; // รับค่า user_birthDate จากฟอร์ม
    $user_age = $_POST['user_age']; // รับค่า user_age จากฟอร์ม
    $user_leaveDays = $_POST['user_leaveDays']; // รับค่า user_leaveDays จากฟอร์ม
    $user_username = $_POST['user_username']; // รับค่า user_username จากฟอร์ม
    $user_password = $_POST['user_password']; // รับค่า user_password จากฟอร์ม
    $user_gender = $_POST['user_gender']; // รับค่า user_gender จากฟอร์ม

    // แปลงวันที่จาก พ.ศ. เป็น ค.ศ. สำหรับอายุงาน
    $date_parts = explode('/', $user_startDate);
    if (count($date_parts) == 3) {
        // สมมติว่า $date_parts[0] = วัน, $date_parts[1] = เดือน, $date_parts[2] = ปี
        $day = intval($date_parts[0]);
        $month = intval($date_parts[1]);
        $year = intval($date_parts[2]) - 543; // แปลง พ.ศ. เป็น ค.ศ.

        // สร้างวันที่ในรูปแบบ ปี ค.ศ.-เดือน-วัน
        $formatted_startDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
    } else {
        die("วันที่ไม่ถูกต้อง");
    }

    // แปลงวันที่จาก พ.ศ. เป็น ค.ศ. สำหรับอายุ
    $date_parts = explode('/', $user_birthDate);
    if (count($date_parts) == 3) {
        // สมมติว่า $date_parts[0] = วัน, $date_parts[1] = เดือน, $date_parts[2] = ปี
        $day = intval($date_parts[0]);
        $month = intval($date_parts[1]);
        $year = intval($date_parts[2]) - 543; // แปลง พ.ศ. เป็น ค.ศ.

        // สร้างวันที่ในรูปแบบ ปี ค.ศ.-เดือน-วัน
        $formatted_user_age = sprintf('%04d-%02d-%02d', $year, $month, $day);
    } else {
        die("วันที่ไม่ถูกต้อง");
    }

    $db = new class_conn();
    $conn = $db->connect();

    // ดึง user_no ล่าสุด
    $sql_last_no = "SELECT user_no FROM tb_user WHERE user_no LIKE 'F_%' ORDER BY user_no DESC LIMIT 1";
    $result = $conn->query($sql_last_no);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_no = $row['user_no'];

        // แยกส่วนตัวเลขจาก user_no
        $last_no_int = intval(substr($last_no, 2));

        // เพิ่มลำดับเลขต่อไป
        $new_no = 'F_' . str_pad($last_no_int + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $new_no = 'F_001';
    }

    // แทรกข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO tb_user (user_no, user_name, user_startDate, user_exp, user_idNumber, user_phoneNumber, user_Date, user_age, user_day, user_username, user_password, user_gender, position_id)
            VALUES ('$new_no', '$user_name', '$formatted_startDate', '$user_exp', '$user_idNumber', '$user_phoneNumber', '$formatted_user_age', '$user_age', '$user_leaveDays', '$user_username', '$user_password', '$user_gender', '6')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Successful - New user No: $new_no'); window.location.href = 'show_position6.php';</script>";
        exit();
    } else {
        echo "<div style='color: red; text-align: center;'>เกิดข้อผิดพลาด: " . $conn->error . "</div>";
    }

    $conn->close();
}
?>