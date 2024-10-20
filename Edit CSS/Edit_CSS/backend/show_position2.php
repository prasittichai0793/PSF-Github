<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แผนกพนักงานขับรถขนย้าย</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="icon" href="../images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <?php include '../class_conn.php'; ?>
    <?php include 'sidebar.php'; ?>
    <style>
        body {
            margin: 0;
            font-family: Verdana, sans-serif;
            background-color: #FFDEDE;
        }

        .display-container {
            position: fixed;
            top: 20px;
            left: 270px;
            right: 20px;
            bottom: 20px;
            display: flex;
            flex-direction: column;
            transition: left 0.3s ease;
        }

        .tool-for-table-container {
            background-color: #FFB8B8;
            padding: 0px 10px;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tool-for-table-container span {
            padding: 0px 20px;
            font-size: 30px;
        }

        .button-tool-for-table-container a,
        .button-tool-for-table-container button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            margin-left: 10px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }

        .free-space {
            width: 100%;
            height: 20px;
            background-color: #FFDEDE;
        }

        .table-container {
            overflow: hidden;
            background-color: #ffcece;
            flex-grow: 1;
            height: auto;
            padding: 10px;
            box-sizing: border-box;
        }

        .table-container-inner {
            overflow-y: auto;
            height: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 20px;
            text-align: left;
            white-space: nowrap;
            position: relative;
        }

        th {
            background-color: #FFB8B8;
            color: white;
        }

        thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #FFB8B8;
        }

        tbody tr:nth-child(odd) {
            background-color: #FFDEDE;
        }

        tbody tr:nth-child(even) {
            background-color: #ffcece;
        }

        td::before,
        th::before {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            width: 1px;
            height: 80%;
            background-color: black;
            transform: translateY(-50%);
        }

        td:last-child::before,
        th:last-child::before {
            display: none;
        }

        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #888;
            border: 2px solid #f1f1f1;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        ::-webkit-scrollbar-horizontal {
            height: 12px;
        }

        ::-webkit-scrollbar-track-horizontal {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb-horizontal {
            background-color: #888;
            border-radius: 10px;
            border: 2px solid #f1f1f1;
        }

        @media screen and (max-width: 930px) {
            .display-container {
                position: fixed;
                background-color: #ff00bf;
                top: 10px;
                left: 70px;
                right: 10px;
                bottom: 10px;
                display: flex;
                flex-direction: column;
            }

            .tool-for-table-container {
                background-color: #FFB8B8;
                padding: 0px 10px;
                height: 50px;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            .button-tool-for-table-container {
                margin-left: auto;
                display: flex;
                align-items: center;
            }

            .tool-for-table-container span {
                padding: 0px 15px;
                font-size: 20px;
            }

            .button-tool-for-table-container a,
            .button-tool-for-table-container button {
                background-color: #3498db;
                color: white;
                border: none;
                padding: 10px 15px;
                margin-left: 10px;
                font-size: 12px;
                cursor: pointer;
                border-radius: 5px;
                text-decoration: none;
            }

            .table-container {
                overflow: hidden;
                background-color: #ffcece;
                flex-grow: 1;
                height: auto;
                padding: 10px;
                box-sizing: border-box;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="display-container">
        <div class="tool-for-table-container">
            <span>แผนกพนักงานขับรถขนย้าย</span>
            <div class="button-tool-for-table-container">
                <a href="add_pos2.php" class="btn btn-add"><i class="fa fa-plus"></i> เพิ่มพนักงาน</a>
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
                            <th scope="col">NO</th>
                            <th scope="col">ชื่อ</th>
                            <th scope="col">วันเริ่มงาน</th>
                            <th scope="col">อายุงาน</th>
                            <th scope="col">เลขบัตรประชาชน</th>
                            <th scope="col">เบอร์โทรศัทพ์</th>
                            <th scope="col">วัน/เดือน/ปี เกิด</th>
                            <th scope="col">อายุ</th>
                            <th scope="col">วันลา</th>
                            <th scope="col">username</th>
                            <th scope="col">password</th>
                            <th scope="col">เพศ</th>
                            <th scope="col">ตำแหน่ง</th>
                            <th scope="col">แก้ไข</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Create object of class_conn
                        $db = new class_conn();
                        $conn = $db->connect();

                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Fetch data from tb_position
                        $sql = "SELECT * FROM tb_user WHERE position_id = 2";
                        $result = $conn->query($sql);

                        // Check if there are results
                        if ($result->num_rows > 0) {
                            // Loop through and display data
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>{$row['user_id']}</td>
                                <td>{$row['user_no']}</td>
                                <td>{$row['user_name']}</td>
                                <td>{$row['user_startDate']}</td>
                                <td>{$row['user_exp']}</td>
                                <td>{$row['user_idNumber']}</td>
                                <td>{$row['user_phoneNumber']}</td>
                                <td>{$row['user_Date']}</td>
                                <td>{$row['user_age']}</td>
                                <td>{$row['user_day']}</td>
                                <td>{$row['user_username']}</td>
                                <td>{$row['user_password']}</td>
                                <td>{$row['user_gender']}</td>
                                <td>{$row['position_id']}</td>
                                <td>
                                    <a href='edit_pos2.php?id={$row['user_id']}'>แก้ไข</a>
                                    <a href='delete_pos2.php?user_no=" . $row['user_no'] . "' class='btn btn-delete' onclick='return confirm(\"คุณต้องการลบข้อมูลพนักงานนี้หรือไม่?\")'>ลบ</a>
                            </tr>";
                            }
                        }

                        // Close connection
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