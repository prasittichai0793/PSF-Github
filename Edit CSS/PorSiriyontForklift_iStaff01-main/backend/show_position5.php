<?php include 'sidebar.php'; ?>
<?php include '../class_conn.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แผนกHR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/position_5.css"> 
    <script src="../print.js" defer></script>
</head>

<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>แผนกHR</h1>
                <div class="actions">
                <a href="add_pos5.php" class="btn btn-add"><i class="fa fa-plus"></i> เพิ่มพนักงาน</a>
                    <button class="btn btn-save" onclick="printPage()"><i class="fa fa-save"></i> พิมพ์</button>
                </div>
            </header>
            <div class="content">
                <table class="table">
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
                        $sql = "SELECT * FROM tb_hr";
                        $result = $conn->query($sql);

                        // Check if there are results
                        if ($result->num_rows > 0) {
                            // Loop through and display data
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>{$row['hr_id']}</td>
                                <td>{$row['hr_no']}</td>
                                <td>{$row['hr_name']}</td>
                                <td>{$row['hr_startDate']}</td>
                                <td>{$row['hr_exp']}</td>
                                <td>{$row['hr_idNumber']}</td>
                                <td>{$row['hr_phoneNumber']}</td>
                                <td>{$row['hr_Date']}</td>
                                <td>{$row['hr_age']}</td>
                                <td>{$row['hr_username']}</td>
                                <td>{$row['hr_password']}</td>
                                <td>{$row['hr_gender']}</td>
                                <td>{$row['position_id']}</td>
                                <td>
                                    <a href='edit_pos5.php?id={$row['hr_id']}'>แก้ไข</a>
                                    <a href='delete_pos.php?hr_id=" . $row['hr_id'] . "' class='btn btn-delete' onclick='return confirm(\"คุณต้องการลบข้อมูลพนักงานนี้หรือไม่?\")'>ลบ</a>
                                </td>
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
