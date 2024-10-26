<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แจ้งลาพักร้อน</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="icon" href="../images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <?php include '../class_conn.php'; ?>
    <?php include 'sidebar.php'; ?>
    <link rel="stylesheet" href="css/container-table.css">
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
            /* border-radius: 4px; */
        }

        .status-red {
            background-color: lightcoral;
            padding: 5px;
            /* border-radius: 4px; */
        }
    </style>
</head>

<body>
    <div class="display-container">
        <div class="tool-for-table-container">
            <span>แจ้งลาพักร้อน</span>
            <div class="button-tool-for-table-container">
                <button class="btn btn-save" onclick="printPage()"><i class="fa fa-save"></i> พิมพ์</button>
            </div>
        </div>

        <div class=" free-space"></div>

        <div class="table-container">
            <div class="table-container-inner">
                <table id="dataTable">
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

                        // ดึงข้อมูลจาก tb_vacationleave
                        $sql = "SELECT * FROM tb_vacationleave";
                        $result = $conn->query($sql);

                        // ตรวจสอบว่ามีข้อมูลหรือไม่
                        if ($result->num_rows > 0) {
                            // วนลูปแสดงผลข้อมูล
                            while ($row = $result->fetch_assoc()) {
                                $statusClass = '';
                                if ($row['VLeave_status'] == 'อนุมัติ') {
                                    $statusClass = 'status-green';
                                } elseif ($row['VLeave_status'] == 'ไม่อนุมัติ') {
                                    $statusClass = 'status-red';
                                }

                                echo "<tr>
                                <td>{$row['VLeave_id']}</td>
                                <td>{$row['user_id']}</td>
                                <td>{$row['VLeave_dateStart']}</td>
                                <td>{$row['VLeave_dateEnd']}</td>
                                <td>{$row['VLeave_detail']}</td>
                                <td>{$row['VLeave_docs']}</td>
                                <td>{$row['VLeave_date']}</td>
                                <td class='{$statusClass}'>{$row['VLeave_status']}</td>";

                                // แสดง HR ID ที่อนุมัติ/ไม่อนุมัติ หรือแสดงปุ่ม
                                if ($row['VLeave_status'] == 'อนุมัติ' || $row['VLeave_status'] == 'ไม่อนุมัติ') {
                                    echo "<td>{$row['hr_id']}</td>";
                                } else {
                                    echo "<td>
                                        <form method='post' action=''>
                                            <input type='hidden' name='vleave_id' value='{$row['VLeave_id']}'>
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

    <script>
        function printPage() {
            // Get the page title
            var pageTitle = document.title; // ใช้ title ของหน้าแทน h1
            var currentDate = new Date().toLocaleDateString('th-TH', { day: '2-digit', month: '2-digit', year: 'numeric' });

            // Create a new window for printing
            var printWindow = window.open('', '', 'height=842,width=595'); // A4 size in pixels (approx)
            printWindow.document.write('<html><head><title>' + pageTitle + '</title>');
            printWindow.document.write('<link rel="stylesheet" href="print.css">'); // Link to print.css
            printWindow.document.write('<style>@media print { body { font-family: Arial, sans-serif; margin: 0; padding: 0; } .table { width: 100%; border-collapse: collapse; } .table th, .table td { border: 1px solid #000; padding: 5px; text-align: left; } .header-print { text-align: center; margin-bottom: 10mm; } .actions, .btn { display: none; } }</style>');
            printWindow.document.write('</head><body>');

            printWindow.document.write('<div class="header-print">');
            printWindow.document.write('<h2>บริษัท ป.ศิริยนต์โฟล์คลิฟ จำกัด</h2>');
            printWindow.document.write('<p>วันที่: ' + currentDate + '</p>'); // Corrected date format
            printWindow.document.write('</div>');

            // Get the table HTML from the current page
            var tableHTML = document.querySelector('#dataTable').outerHTML; // Get the entire table
            printWindow.document.write(tableHTML); // Write the table to the print window

            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
    </script>
</body>

</html>

<?php
// ตรวจสอบการ submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vleave_id = $_POST['vleave_id'];
    $hr_id = $_SESSION['hr_id']; // ดึง hr_id จาก session

    // สร้าง object class_conn
    $db = new class_conn();
    $conn = $db->connect();

    if (isset($_POST['approve'])) {
        // เปลี่ยนสถานะเป็น 'อนุมัติ'
        $sql = "UPDATE tb_vacationleave SET VLeave_status='อนุมัติ', hr_id='$hr_id' WHERE VLeave_id='$vleave_id'";
    } elseif (isset($_POST['reject'])) {
        // เปลี่ยนสถานะเป็น 'ไม่อนุมัติ'
        $sql = "UPDATE tb_vacationleave SET VLeave_status='ไม่อนุมัติ', hr_id='$hr_id' WHERE VLeave_id='$vleave_id'";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('สถานะได้รับการปรับปรุงแล้ว'); window.location.href = window.location.href;</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    // ปิดการเชื่อมต่อ
    $conn->close();
}
?>