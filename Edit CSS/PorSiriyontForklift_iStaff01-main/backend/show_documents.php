<?php
session_start(); // เริ่ม session
include 'sidebar.php';
include '../class_conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แจ้งยื่นเอกสารเพิ่มเติม</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/position_1.css">
    <script src="../print.js" defer></script>
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
            border-radius: 4px;
        }
        .status-red {
            background-color: lightcoral;
            padding: 5px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>แจ้งยื่นเอกสารเพิ่มเติม</h1>
                <div class="actions">
                    <button class="btn btn-save" onclick="printPage()"><i class="fa fa-save"></i> พิมพ์</button>
                </div>
            </header>
            <div class="content">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">user</th>
                            <th scope="col">ประเภทเอกสาร</th>
                            <th scope="col">เอกสาร</th>
                            <th scope="col">วันที่ทำการ</th>
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

                        // ดึงข้อมูลจาก tb_documents
                        $sql = "SELECT * FROM tb_documents";
                        $result = $conn->query($sql);

                        // ตรวจสอบว่ามีข้อมูลหรือไม่
                        if ($result->num_rows > 0) {
                            // วนลูปแสดงผลข้อมูล
                            while ($row = $result->fetch_assoc()) {
                                $statusClass = '';
                                if ($row['docs_status'] == 'อนุมัติ') {
                                    $statusClass = 'status-green';
                                } elseif ($row['docs_status'] == 'ไม่อนุมัติ') {
                                    $statusClass = 'status-red';
                                }

                                echo "<tr>
                                <td>{$row['docs_id']}</td>
                                <td>{$row['user_id']}</td>
                                <td>{$row['docs_type']}</td>
                                <td>{$row['docs_files']}</td>
                                <td>{$row['docs_date']}</td>
                                <td class='{$statusClass}'>{$row['docs_status']}</td>";

                                // แสดง HR ID ที่อนุมัติ/ไม่อนุมัติ หรือแสดงปุ่ม
                                if ($row['docs_status'] == 'อนุมัติ' || $row['docs_status'] == 'ไม่อนุมัติ') {
                                    echo "<td>{$row['hr_id']}</td>";
                                } else {
                                    echo "<td>
                                        <form method='post' action=''>
                                            <input type='hidden' name='docs_id' value='{$row['docs_id']}'>
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
</body>
</html>

<?php
// ตรวจสอบการ submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $docs_id = $_POST['docs_id'];
    $hr_id = $_SESSION['hr_id']; // ดึง hr_id จาก session

    // สร้าง object class_conn
    $db = new class_conn();
    $conn = $db->connect();

    if (isset($_POST['approve'])) {
        // เปลี่ยนสถานะเป็น 'อนุมัติ'
        $sql = "UPDATE tb_documents SET docs_status='อนุมัติ', hr_id='$hr_id' WHERE docs_id='$docs_id'";
    } elseif (isset($_POST['reject'])) {
        // เปลี่ยนสถานะเป็น 'ไม่อนุมัติ'
        $sql = "UPDATE tb_documents SET docs_status='ไม่อนุมัติ', hr_id='$hr_id' WHERE docs_id='$docs_id'";
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
