<?php
session_start();
include 'class_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new class_conn();
    $connection = $conn->connect();

    $username = $_POST['username'];
    $password = $_POST['password'];

    // ใช้ Prepared Statements เพื่อป้องกัน SQL Injection
    // ตรวจสอบในตาราง tb_user
    $user_query = "SELECT * FROM tb_user WHERE user_username = ? AND user_password = ?";
    $stmt = mysqli_prepare($connection, $user_query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $user_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($user_result) == 1) {
        $user_data = mysqli_fetch_assoc($user_result);
        $_SESSION['user_type'] = 'user';
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user_data['user_id'];
        header("Location: frontend/show_profile.php");
        exit();
    }

    // ตรวจสอบในตาราง tb_hr
    $hr_query = "SELECT * FROM tb_hr WHERE hr_username = ? AND hr_password = ?";
    $stmt = mysqli_prepare($connection, $hr_query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $hr_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($hr_result) == 1) {
        $hr_data = mysqli_fetch_assoc($hr_result);
        $_SESSION['user_type'] = 'hr';
        $_SESSION['username'] = $username;
        $_SESSION['hr_id'] = $hr_data['hr_id'];
        header("Location: backend/show_position.php");
        exit();
    }

    // ตรวจสอบในตาราง tb_admin
    $admin_query = "SELECT * FROM tb_admin WHERE admin_username = ? AND admin_password = ?";
    $stmt = mysqli_prepare($connection, $admin_query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $admin_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($admin_result) == 1) {
        $admin_data = mysqli_fetch_assoc($admin_result);
        $_SESSION['user_type'] = 'admin';
        $_SESSION['username'] = $username;
        $_SESSION['admin_id'] = $admin_data['admin_id'];
        header("Location: backend/show_position.php");
        exit();
    }

    $_SESSION['login_error'] = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PorSiriyontForklift</title>
    <style>
* {
  box-sizing: border-box;
}
body {
  font-family: Verdana, sans-serif;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #FFDEDE;
}

.login-container {
  display: flex;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
  overflow: hidden;
  width: 700px;
  height: 500px;
}

.left {
  background-color: #FFF0F0;
  width: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  padding: 20px;
}

.left img {
  max-width: 50%;
  height: auto;
  margin-bottom: 20px;
  border-radius: 8px;
}

.right {
  background-color: #FFB8B8;
  width: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 30px;
}

h2, h1 {
  margin: 0;
  padding: 0;
  font-size: 25px;
  text-align: center;
  color: #C43B3B;
}

input[type="text"], input[type="password"] {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 5px;
}

input[type="submit"] {
  width: 80%;
  padding: 10px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 50px;
  cursor: pointer;
  margin-top: 20px;
  margin-left: 30px;
}

input[type="submit"]:hover {
  background-color: #45a049;
}

@media screen and (max-width: 768px) {
  .login-container {
    flex-direction: column;
    width: 90%;
    height: auto;
  }
  
  .left, .right {
    width: 100%;
  }
  
  .left {
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
    padding-left: 20px;
    height: auto;
  }

  .left img {
    max-width: 20%;
    margin-right: 10px;
  }
  
  .left h2 {
    margin-left: 0;
  }

  .right {
    padding: 20px;
    height: 250px;
  }
  .right h1 {
    margin-bottom: 10px;
  }
}
</style>
</head>
<body>
    <div class="login-container">
        <div class="left">
            <img src="images/logo.jpg" alt="Logo">
            <h2>PorSiriyontForklift</h2>
        </div>
        <div class="right">
            <div>
                <h1>LOGIN</h1>
                <?php
                if (isset($_SESSION['login_error'])) {
                    echo "<p style='color: red; text-align: center;'>" . $_SESSION['login_error'] . "</p>";
                    unset($_SESSION['login_error']);
                }
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>
    </div>
</body>
</html>