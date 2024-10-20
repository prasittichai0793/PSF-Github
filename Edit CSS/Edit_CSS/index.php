<?php
session_start();
include 'class_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new class_conn();
    $connection = $conn->connect();

    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // ตรวจสอบในตาราง tb_user
    $user_query = "SELECT * FROM tb_user WHERE user_username = '$username' AND user_password = '$password'";
    $user_result = mysqli_query($connection, $user_query);

    if (mysqli_num_rows($user_result) == 1) {
        $_SESSION['user_type'] = 'user';
        $_SESSION['username'] = $username;
        header("Location: frontend/main.php");
        exit();
    }

    // ตรวจสอบในตาราง tb_hr
    $hr_query = "SELECT * FROM tb_hr WHERE hr_username = '$username' AND hr_password = '$password'";
    $hr_result = mysqli_query($connection, $hr_query);

    if (mysqli_num_rows($hr_result) == 1) {
        $_SESSION['user_type'] = 'hr';
        $_SESSION['username'] = $username;
        header("Location: backend/show_position.php");
        exit();
    }

    // ตรวจสอบในตาราง tb_admin
    $admin_query = "SELECT * FROM tb_admin WHERE admin_username = '$username' AND admin_password = '$password'";
    $admin_result = mysqli_query($connection, $admin_query);

    if (mysqli_num_rows($admin_result) == 1) {
        $_SESSION['user_type'] = 'admin';
        $_SESSION['username'] = $username;
        header("Location: backend/show_position.php");
        exit();
    }

    // ถ้าไม่พบข้อมูลในทุกตาราง
    $_SESSION['login_error'] = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="images/Logo_PSF_BG_White_Favicon.png" type="image/x-icon">
    <style>
        body {
            font-family: Verdana, sans-serif;
            background-color: #FFDEDE;
            margin: 0px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Desktop */
        @media screen and (min-width: 931px) {
            .container-login {
                background-color: #00ff22;
                display: flex;
                width: 75%;
                max-width: 800px;
                height: 500px;
                border-radius: 10px;
                overflow: hidden;
            }

            .left-top-container {
                background-color: #FFF0F0;
                color: #000000;
                font-weight: bold;
                flex: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                padding: 40px;
            }

            .img_ltc img {
                height: 170px;
                width: auto;
                margin: 20px;
            }

            .label_ltc_PSF {
                font-size: 25px;
            }

            .right-buttom-container {
                background-color: #FFB8B8;
                flex: 1;
                display: flex;
                justify-content: center;
                flex-direction: column;
                padding: 40px;
            }

            .label_rbc_PSF {
                font-size: 25px;
                font-weight: bold;
                margin-bottom: 25px;
            }

            form {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .input-field-group {
                position: relative;
                width: 100%;
                margin-bottom: 20px;
            }

            .input-field-group label {
                position: absolute;
                top: 50%;
                left: 15px;
                transform: translateY(-50%);
                color: #000000;
                font-size: 15px;
                pointer-events: none;
                transition: 0.3s;
            }

            .input-field-group input[type="text"],
            .input-field-group input[type="password"] {
                font-size: 20px;
                color: #000000;
                outline: none;
                border: none;
                width: 100%;
                height: 50px;
                box-sizing: border-box;
                padding: 10px;
                border-radius: 5px;
            }

            input[type="text"]:focus,
            input[type="text"]:valid,
            input[type="password"]:focus,
            input[type="password"]:valid {
                border: 2px solid #000000;
            }

            input[type="text"]:focus~label,
            input[type="text"]:valid~label,
            input[type="password"]:focus~label,
            input[type="password"]:valid~label {
                top: 0;
                left: 15px;
                font-size: 12px;
                padding: 1px 10px;
                background: #ffffff;
                border: 2px solid #000000;
                border-radius: 5px;
            }

            input[type="submit"] {
                width: 200px;
                height: 35px;
                font-weight: bold;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 17px;
                border-radius: 50px;
            }

            input[type="submit"]:hover {
                background-color: #45a049;
            }
        }

        @media screen and (max-width: 930px) {
            .container-login {
                background-color: #00ff22;
                display: flex;
                width: 90%;
                max-width: 400px;
                height: 350px;
                border-radius: 10px;
                overflow: hidden;
                flex-direction: column;
            }

            .left-top-container {
                background-color: #FFF0F0;
                color: #000000;
                height: 20px;
                flex: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }

            .img_ltc img {
                height: 70px;
                width: auto;
                margin-right: 10px;
                padding: 0;
            }

            .label_ltc_PSF {
                font-size: 20px;
                font-weight: bold;
            }

            .right-buttom-container {
                background-color: #FFB8B8;
                flex: 1;
                display: flex;
                justify-content: center;
                flex-direction: column;
                padding: 20px;
            }

            .label_rbc_PSF {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 25px;
            }

            form {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .input-field-group {
                position: relative;
                width: 100%;
                margin-bottom: 20px;
            }

            .input-field-group label {
                position: absolute;
                top: 50%;
                left: 15px;
                transform: translateY(-50%);
                color: #000000;
                font-size: 15px;
                pointer-events: none;
                transition: 0.3s;
            }

            .input-field-group input[type="text"],
            .input-field-group input[type="password"] {
                font-size: 20px;
                color: #000000;
                outline: none;
                border: none;
                width: 100%;
                height: 40px;
                box-sizing: border-box;
                padding: 15px;
                border-radius: 5px;
            }

            input[type="text"]:focus,
            input[type="text"]:valid,
            input[type="password"]:focus,
            input[type="password"]:valid {
                border: 2px solid #000000;
            }

            input[type="text"]:focus~label,
            input[type="text"]:valid~label,
            input[type="password"]:focus~label,
            input[type="password"]:valid~label {
                top: 0;
                left: 15px;
                font-size: 12px;
                padding: 0px 10px;
                background: #ffffff;
                border: 2px solid #000000;
                border-radius: 5px;
            }

            input[type="submit"] {
                width: 200px;
                height: 35px;
                font-weight: bold;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 17px;
                border-radius: 50px;
            }

            input[type="submit"]:hover {
                background-color: #45a049;
            }
        }
    </style>
</head>

<body>
    <div class="container-login">
        <div class="left-top-container">
            <div class="img_ltc">
                <img src="images/Logo_PSF_BG_White.png" alt="Logo">
            </div>
            <div class="label_ltc_PSF">
                PorSiriyontForklift
            </div>
        </div>
        <div class="right-buttom-container">
            <?php
            if (isset($_SESSION['login_error'])) {
                echo "<p style='color: red;'>" . $_SESSION['login_error'] . "</p>";
                unset($_SESSION['login_error']);
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="label_rbc_PSF">
                    LOGIN
                </div>
                <div class="input-field-group">
                    <input type="text" id="username" name="username" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-field-group">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Password</label>
                </div>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>

</body>

</html>