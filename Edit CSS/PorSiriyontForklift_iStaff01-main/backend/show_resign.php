<?php
session_start(); // Start session
include 'sidebar.php';
include '../class_conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แจ้งลาออก</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/position_1.css">
    <script src="../print.js" defer></script>
    <style>
        /* Styles for buttons and status indicators */
        .btn-approve { background-color: green; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-reject { background-color: red; color: white; padding: 5px 10px; border: none; border-radius: 4px; }
        .status-green { background-color: lightgreen; padding: 5px; border-radius: 4px; }
        .status-red { background-color: lightcoral; padding: 5px; border-radius: 4px; }
    </style>
</head>

<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>แจ้งลาออก</h1>
                <div class="actions">
                    <button class="btn btn-save" onclick="printPage()"><i class="fa fa-save"></i> พิมพ์</button>
                </div>
            </header>
            <div class="content">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>วันทำการ</th>
                            <th>เหตุผล</th>
                            <th>เอกสาร</th>
                            <th>ทำงานวันสุดท้าย</th>
                            <th>Admin ที่อนุมัติ</th>
                            <th>สถานะ</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $db = new class_conn();
                        $conn = $db->connect();

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM tb_resign";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $statusClass = ($row['resign_status'] == 'อนุมัติ') ? 'status-green' : (($row['resign_status'] == 'ไม่อนุมัติ') ? 'status-red' : '');

                                echo "<tr>
                                <td>{$row['resign_id']}</td>
                                <td>{$row['user_id']}</td>
                                <td>{$row['resign_logdate']}</td>
                                <td>{$row['resign_detail']}</td>
                                <td>{$row['resign_docs']}</td>
                                <td>{$row['resign_date']}</td>
                                <td>{$row['admin_id']}</td>
                                <td class='{$statusClass}'>{$row['resign_status']}</td>";

                                if (empty($row['admin_id'])) {
                                    echo "<td>
                                        <form method='post' action=''>
                                            <input type='hidden' name='resign_id' value='{$row['resign_id']}'>"; 
                                    echo "<button class='btn-approve' name='approve_admin'>Admin อนุมัติ</button>
                                          <button class='btn-reject' name='reject_admin'>Admin ไม่อนุมัติ</button>";
                                    echo "</form>
                                    </td>";
                                } else {
                                    echo "<td>การอนุมัติเรียบร้อย</td>";
                                }
                                echo "</tr>";
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

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resign_id = $_POST['resign_id'];
    $admin_id = $_SESSION['admin_id'];

    $db = new class_conn();
    $conn = $db->connect();

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "";

    if (isset($_POST['approve_admin'])) {
        $sql = "UPDATE tb_resign SET admin_id='$admin_id' WHERE resign_id='$resign_id'";
    } elseif (isset($_POST['reject_admin'])) {
        $sql = "UPDATE tb_resign SET resign_status='ไม่อนุมัติ', admin_id='$admin_id' WHERE resign_id='$resign_id'";
    }

    if ($sql && $conn->query($sql) === TRUE) {
        // Update the status to "อนุมัติ" if admin has approved
        if (isset($_POST['approve_admin'])) {
            $updateStatusSql = "UPDATE tb_resign SET resign_status='อนุมัติ' WHERE resign_id='$resign_id'";
            $conn->query($updateStatusSql);
        }

        // If rejected, update the status to "ไม่อนุมัติ"
        if (isset($_POST['reject_admin'])) {
            $updateStatusSql = "UPDATE tb_resign SET resign_status='ไม่อนุมัติ' WHERE resign_id='$resign_id'";
            $conn->query($updateStatusSql);
        }

        echo "<script>alert('สถานะได้รับการปรับปรุงแล้ว'); window.location.href = window.location.href;</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
