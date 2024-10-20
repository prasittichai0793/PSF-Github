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
                    <button class="btn btn-add"><i class="fa fa-plus"></i> เพิ่มหัวข้อ</button>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $db = new class_conn();
                        $conn = $db->connect();

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM tb_position";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>{$row['position_id']}</td>
                                <td>{$row['position_type']}</td>
                                <td>{$row['position_name']}</td>
                            </tr>";
                            }
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>