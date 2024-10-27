<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data</title>

    <link rel="icon" href="../images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <?php include '../class_conn.php'; ?>

    <style>
        body {
            margin: 0;
            font-family: Verdana, sans-serif;
        }

        .display-container {
            width: 100%;
            height: 100vh;
        }

        .container-content {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            width: 60%;
            max-width: 700px;
        }

        .content span {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content label {
            font-size: 15px;
        }

        .content input,
        .content select {
            padding: 5px;
            font-size: 14px;
            width: 100%;
            height: auto;
            box-sizing: border-box;
        }

        .content button {
            font-size: 14px;
            color: #fff;
            padding: 10px 15px;
            margin-top: 10px;
            border: none;
        }

        .btn-save {
            background: #259b24;
        }

        .btn-save:hover {
            background: #056f00;
            color: #fff;
        }

        .btn-cancel {
            background: #e51c23;
        }

        .btn-cancel:hover {
            background: #b0120a;
            color: #fff;
        }

        .fa-save,
        .fa-times {
            margin-right: 5px;
        }

        .form-group {
            padding: 5px 0px;
        }

        @media screen and (max-width: 930px) {
            .content {
                min-width: 150px;
            }

            .content button {
                font-size: 12px;
                color: #fff;
                padding: 5px 10px;
                margin-top: 10px;
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="display-container">
        <div class="display-content">
            <div class="container-content">
                <div class="content">
                    <span>Insert Data</span>
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
                        <button type="button" class="btn btn-cancel" onclick="location.href='show_data.php'"><i
                                class="fas fa-times"></i>ยกเลิก</button>
                    </form>
                </div>
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
</body>

</html>

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
        // echo "<p>Data inserted successfully</p>";
        echo "<script>alert('Data inserted successfully : $user_id'); window.location.href = 'show_data.php';</script>";
        exit();
    } else {
        echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }

    $conn->close();
}
?>