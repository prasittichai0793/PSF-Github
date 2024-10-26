<?php
session_start();
include 'class_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new class_conn();
    $connection = $conn->connect();

    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // // ตรวจสอบในตาราง tb_user
    // $user_query = "SELECT * FROM tb_user WHERE user_username = '$username' AND user_password = '$password'";
    // $user_result = mysqli_query($connection, $user_query);

    // if (mysqli_num_rows($user_result) == 1) {
    //     $_SESSION['user_type'] = 'user';
    //     $_SESSION['username'] = $username;
    //     header("Location: frontend/show_profile.php");
    //     exit();
    // }

    // // ตรวจสอบในตาราง tb_hr
    // $hr_query = "SELECT * FROM tb_hr WHERE hr_username = '$username' AND hr_password = '$password'";
    // $hr_result = mysqli_query($connection, $hr_query);

    // if (mysqli_num_rows($hr_result) == 1) {
    //     $_SESSION['user_type'] = 'hr';
    //     $_SESSION['username'] = $username;
    //     header("Location: backend/show_position.php");
    //     exit();
    // }

    // ตรวจสอบในตาราง tb_user
    $hr_query = "SELECT user_id, user_name FROM tb_user WHERE user_username = '$username' AND user_password = '$password'";
    $hr_result = mysqli_query($connection, $hr_query);

    if (mysqli_num_rows($hr_result) == 1) {
        $hr_data = mysqli_fetch_assoc($hr_result);
        $_SESSION['user_type'] = 'user';
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $hr_data['user_id'];  // เก็บ hr_id ใน session
        $_SESSION['user_name'] = $hr_data['user_name'];  // เก็บ hr_name ใน session
        header("Location: frontend/show_profile.php");
        exit();
    }

    // ตรวจสอบในตาราง tb_hr
    $hr_query = "SELECT hr_id, hr_name FROM tb_hr WHERE hr_username = '$username' AND hr_password = '$password'";
    $hr_result = mysqli_query($connection, $hr_query);

    if (mysqli_num_rows($hr_result) == 1) {
        $hr_data = mysqli_fetch_assoc($hr_result);
        $_SESSION['user_type'] = 'hr';
        $_SESSION['username'] = $username;
        $_SESSION['hr_id'] = $hr_data['hr_id'];  // เก็บ hr_id ใน session
        $_SESSION['hr_name'] = $hr_data['hr_name'];  // เก็บ hr_name ใน session
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
    <link rel="stylesheet" href="css/index.css">
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