<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E เพิ่มข้อมูลพนักงานHR</title>

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

        .hr_exp {
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
                    <span>E เพิ่มข้อมูลพนักงานHR</span>
                    <form action="add_pos5.php" method="post">
                        <div class="form-group">
                            <label for="hr_name">ชื่อ:</label>
                            <input type="text" id="hr_name" name="hr_name" required autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label class="info-label">เริ่มวันที่:</label>
                            <input class="input-medium" type="text" id="hr_startDate" name="hr_startDate"
                                data-provide="datepicker" data-date-language="th-th" autocomplete="off"
                                onchange="calculateExperienceHrStartDate()" onkeydown="return false;"
                                onpaste="return false;">
                        </div>

                        <div class="form-group hr_exp">
                            <label for="hr_exp">อายุงาน:</label>
                            <input type="text" id="hr_exp" name="hr_exp" readonly autocomplete="off">
                        </div>

                        <div class="form-group hr_exp">
                            <label for="hr_leaveDays">วันลา:</label>
                            <input type="text" id="hr_leaveDays" name="hr_leaveDays" readonly autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="hr_idNumber">เลขบัตรประชาชน:</label>
                            <input type="text" id="hr_idNumber" name="hr_idNumber" required maxlength="13"
                                pattern="\d{13}" title="กรุณากรอกเลขบัตรประชาชน 13 ตัวเท่านั้น" autocomplete="off"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                        </div>

                        <div class="form-group">
                            <label for="hr_phoneNumber">เบอร์โทรศัพท์:</label>
                            <input type="text" id="hr_phoneNumber" name="hr_phoneNumber" required maxlength="10"
                                pattern="\d{10}" title="กรุณากรอกเบอร์โทรศัพท์ 10 ตัวเท่านั้น" autocomplete="off"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                        </div>

                        <div class="form-group">
                            <label class="info-label">วัน/เดือน/ปี เกิด:</label>
                            <input class="input-medium" type="text" id="hr_birthDate" name="hr_birthDate"
                                data-provide="datepicker" data-date-language="th-th" autocomplete="off"
                                onchange="calculateExperienceHrBirthDate()" onkeydown="return false;"
                                onpaste="return false;">
                        </div>

                        <div class="form-group hr_exp">
                            <label for="hr_age">อายุ:</label>
                            <input type="text" id="hr_age" name="hr_age" readonly autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="hr_username">Username:</label>
                            <input type="text" id="hr_username" name="hr_username" required autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="hr_password">Password:</label>
                            <input type="password" id="hr_password" name="hr_password" required autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="hr_gender">เพศ:</label>
                            <select id="hr_gender" name="hr_gender" required>
                                <option value="" disabled selected>กรุณาเลือกเพศ</option>
                                <option value="male">ชาย</option>
                                <option value="female">หญิง</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-save"><i class="fa fa-save"></i> บันทึก</button>
                        <button type="button" class="btn btn-cancel" onclick="location.href='show_position5.php'"><i
                                class="fas fa-times"></i>ยกเลิก</button>
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
    $hr_name = $_POST['hr_name']; // รับค่า hr_name จากฟอร์ม
    $hr_startDate = $_POST['hr_startDate']; // รับค่า hr_startDate จากฟอร์ม
    $hr_exp = $_POST['hr_exp']; // รับค่า hr_exp จากฟอร์ม
    $hr_idNumber = $_POST['hr_idNumber']; // รับค่า hr_idNumber จากฟอร์ม
    $hr_phoneNumber = $_POST['hr_phoneNumber']; // รับค่า hr_phoneNumber จากฟอร์ม
    $hr_birthDate = $_POST['hr_birthDate']; // รับค่า hr_birthDate จากฟอร์ม
    $hr_age = $_POST['hr_age']; // รับค่า hr_age จากฟอร์ม
    $hr_username = $_POST['hr_username']; // รับค่า hr_username จากฟอร์ม
    $hr_password = $_POST['hr_password']; // รับค่า hr_password จากฟอร์ม
    $hr_gender = $_POST['hr_gender']; // รับค่า hr_gender จากฟอร์ม

    // แปลงวันที่จาก พ.ศ. เป็น ค.ศ. สำหรับอายุงาน
    $date_parts = explode('/', $hr_startDate);
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
    $date_parts = explode('/', $hr_birthDate);
    if (count($date_parts) == 3) {
        // สมมติว่า $date_parts[0] = วัน, $date_parts[1] = เดือน, $date_parts[2] = ปี
        $day = intval($date_parts[0]);
        $month = intval($date_parts[1]);
        $year = intval($date_parts[2]) - 543; // แปลง พ.ศ. เป็น ค.ศ.

        // สร้างวันที่ในรูปแบบ ปี ค.ศ.-เดือน-วัน
        $formatted_hr_age = sprintf('%04d-%02d-%02d', $year, $month, $day);
    } else {
        die("วันที่ไม่ถูกต้อง");
    }

    $db = new class_conn();
    $conn = $db->connect();

    // ดึง hr_no ล่าสุด
    $sql_last_no = "SELECT hr_no FROM tb_hr WHERE hr_no LIKE 'E_%' ORDER BY hr_no DESC LIMIT 1";
    $result = $conn->query($sql_last_no);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_no = $row['hr_no'];

        // แยกส่วนตัวเลขจาก hr_no
        $last_no_int = intval(substr($last_no, 2));

        // เพิ่มลำดับเลขต่อไป
        $new_no = 'E_' . str_pad($last_no_int + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $new_no = 'E_001';
    }

    // แทรกข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO tb_hr (hr_no, hr_name, hr_startDate, hr_exp, hr_idNumber, hr_phoneNumber, hr_Date, hr_age, hr_username, hr_password, hr_gender, position_id)
            VALUES ('$new_no', '$hr_name', '$formatted_startDate', '$hr_exp', '$hr_idNumber', '$hr_phoneNumber', '$formatted_hr_age', '$hr_age', '$hr_username', '$hr_password', '$hr_gender', '5')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Successful - New hr No: $new_no'); window.location.href = 'show_position5.php';</script>";
        exit();
    } else {
        echo "<div style='color: red; text-align: center;'>เกิดข้อผิดพลาด: " . $conn->error . "</div>";
    }

    $conn->close();
}
?>