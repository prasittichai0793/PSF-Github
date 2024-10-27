<?php include '../class_conn.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<style>
    /* add_all.css */
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    .main-content {
        background: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    header h1 {
        text-align: center;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
    }
    .form-group input, .form-group select {
        width: 97%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .btn {
        display: inline-block;
        padding: 10px 15px;
        color: #fff;
        background: #007bff;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-save {
        background: #28a745;
    }
    .btn i {
        margin-right: 5px;
    }
</style>
<body>
    <div class="container">
        <div class="main-content">
            <header>
                <h1>Insert Data</h1>
            </header>
            <div class="content">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="user_no">User No:</label>
                        <select name="user_no" id="user_no" onchange="updateUserInfo()" required>
                            <option value="">Select User No</option>
                            <?php
                            $db = new class_conn();
                            $conn = $db->connect();
                            $sql = "SELECT user_no, user_id, user_name FROM tb_user";
                            $result = $conn->query($sql);
                            $user_data = [];
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['user_no']}'>{$row['user_no']}</option>";
                                    $user_data[$row['user_no']] = ['user_id' => $row['user_id'], 'user_name' => $row['user_name']];
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user_id">User ID:</label>
                        <input type="text" name="user_id" id="user_id" readonly>
                    </div>
                    <div class="form-group">
                        <label for="user_name">User Name:</label>
                        <input type="text" name="user_name" id="user_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="data_macAddress">MAC Address:</label>
                        <input type="text" name="data_macAddress" id="data_macAddress" required>
                    </div>
                    <div class="form-group">
                        <label for="data_name">Device Name:</label>
                        <input type="text" name="data_name" id="data_name" required>
                    </div>
                    <button type="submit" class="btn btn-save"><i class="fa fa-save"></i> บันทึก</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const userData = <?php echo json_encode($user_data); ?>;
        
        function updateUserInfo() {
            const userNo = document.getElementById("user_no").value;
            if (userNo && userData[userNo]) {
                document.getElementById("user_id").value = userData[userNo].user_id;
                document.getElementById("user_name").value = userData[userNo].user_name;
            } else {
                document.getElementById("user_id").value = "";
                document.getElementById("user_name").value = "";
            }
        }
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $db = new class_conn();
        $conn = $db->connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $user_id = $_POST['user_id'];
        $user_no = $_POST['user_no'];
        $macAddress = $_POST['data_macAddress'];
        $device_name = $_POST['data_name'];

        $sql = "INSERT INTO tb_data (user_id, user_no, data_macAddress, data_name) VALUES ('$user_id', '$user_no', '$macAddress', '$device_name')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('บันทึกข้อมูลสำเร็จ'); window.location.href='show_data.php';</script>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }

        $conn->close();
    }
    ?>
</body>
</html>