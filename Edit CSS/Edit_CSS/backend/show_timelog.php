<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกเวลาเข้า-ออก</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="icon" href="../images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <?php include '../class_conn.php'; ?>
    <?php include 'sidebar.php'; ?>
    <link rel="stylesheet" href="css/container-table.css">
</head>

<body>
    <div class="display-container">
        <div class="tool-for-table-container">
            <span>บันทึกเวลาเข้า-ออก</span>
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
                            <th scope="col">USER</th>
                            <th scope="col">วันที่ทำการ</th>
                            <th scope="col">บันทึกกเวลาเข้างาน</th>
                            <th scope="col">บันทึกกเวลาออกงาน</th>
                            <th scope="col">Data</th>
                            <th scope="col">สถานะ</th>
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
                        $sql = "SELECT * FROM tb_timelog";
                        $result = $conn->query($sql);

                        // Check if there are results
                        if ($result->num_rows > 0) {
                            // Loop through and display data
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>{$row['timelog_id']}</td>
                                <td>{$row['user_id']}</td>
                                <td>{$row['timelog_date']}</td>
                                <td>{$row['timelog_in']}</td>
                                <td>{$row['timelog_out']}</td>
                                <td>{$row['data_id']}</td>
                                <td>{$row['timelog_status']}</td>
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
</body>

</html>