<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แผนกadmin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="icon" href="../images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <?php include '../class_conn.php'; ?>
    <?php include 'sidebar.php'; ?>
    <link rel="stylesheet" href="css/container-table.css">
</head>

<body>
    <div class="display-container">
        <div class="tool-for-table-container">
            <span>แผนกadmin</span>
            <div class="button-tool-for-table-container">
                <a href="add_pos7.php" class="btn btn-add"><i class="fa fa-plus"></i> เพิ่มพนักงาน</a>
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
                            <th scope="col">username</th>
                            <th scope="col">password</th>
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
                        $sql = "SELECT * FROM tb_admin ";
                        $result = $conn->query($sql);

                        // Check if there are results
                        if ($result->num_rows > 0) {
                            // Loop through and display data
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>{$row['admin_id']}</td>
                                <td>{$row['admin_no']}</td>
                                <td>{$row['admin_name']}</td>
                                <td>{$row['admin_username']}</td>
                                <td>{$row['admin_password']}</td>
                                <td>{$row['position_id']}</td>
                                <td>
                                    <a href='edit_pos7.php?id={$row['admin_id']}'class='btn btn-edit'><i class='fa fa-edit'></i>แก้ไข</a>
                                    <a href='delete_pos7.php?admin_no=" . $row['admin_no'] . "' class='btn btn-delete' onclick='return confirm(\"คุณต้องการลบข้อมูลพนักงานนี้หรือไม่?\")'><i class='fa fa-trash'></i>ลบ</a>
                                </td>
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