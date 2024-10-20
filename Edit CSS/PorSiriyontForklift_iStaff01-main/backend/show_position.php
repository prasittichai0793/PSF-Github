<?php include 'sidebar.php'; ?>
<?php include '../class_conn.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>position</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/position.css"> 
    <script src="../print.js" defer></script>
</head>

<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>แผนก</h1>
                <div class="actions">
                <a href="add_pos.php" class="btn btn-add"><i class="fa fa-plus"></i> เพิ่มแผนก</a>
                    <button class="btn btn-save" onclick="printPage()"><i class="fa fa-save"></i> พิมพ์</button>
                </div>
            </header>
            <div class="content">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">ประเภท</th>
                            <th scope="col">ชื่อแผนก</th>
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
                        $sql = "SELECT * FROM tb_position";
                        $result = $conn->query($sql);

                        // Check if there are results
                        if ($result->num_rows > 0) {
                            // Loop through and display data
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>{$row['position_id']}</td>
                                <td>{$row['position_type']}</td>
                                <td>{$row['position_name']}</td>
                                <td>
                                    <a href='add_pos.php?edit_id={$row['position_id']}' class='btn btn-edit'><i class='fa fa-edit'></i> แก้ไข</a>
                                    <a href='delete_pos.php?position_id=" . $row['position_id'] . "' class='btn btn-delete' onclick='return confirm(\"คุณต้องการลบข้อมูลพนักงานนี้หรือไม่?\")'>ลบ</a>
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