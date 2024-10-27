<?php include 'sidebar.php'; ?>
<?php include '../class_conn.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกเวลาเข้า-ออก</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/position_5.css"> 
    <script src="../print.js" defer></script>
</head>

<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>บันทึกเวลาเข้า-ออก</h1>
                <div class="actions">
                    <button class="btn btn-save" onclick="printPage()"><i class="fa fa-save"></i> พิมพ์</button>
                </div>
            </header>
            <div class="content">
                <table class="table">
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
