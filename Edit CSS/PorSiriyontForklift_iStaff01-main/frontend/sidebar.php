<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
</head>

<body>
    <div class="container">
        <nav class="sidebar">
            <ul>

                <li>
                    <a href="show_profile.php" class="dropdown-toggle">
                        <span><i class="fa fa-briefcase"></i> ข้อมูลส่วนตัว</span>
                    </a>
                </li>
                <li>
                    <a href="show_history.php" class="dropdown-toggle">
                        <span><i class="fa fa-calendar-times"></i> ประวัติ</span>
                    </a>
                </li>
                <li>
                    <a href="personalleave.php" class="dropdown-toggle">
                        <span><i class="fa-solid fa-window-restore"></i> ลากิจ</span>
                    </a>
                </li>
                <li>
                    <a href="vacationleave.php" class="dropdown-toggle">
                        <span><i class="fa fa-umbrella-beach"></i> ลาพักร้อน</span>
                    </a>
                </li>
                <li>
                    <a href="resign.php" class="dropdown-toggle">
                        <span><i class="fa fa-user-slash"></i> ลาออก</span>
                    </a>
                </li>
                <li>
                    <a href="documents.php" class="dropdown-toggle">
                        <span><i class="fa fa-user-slash"></i> ยื่นเอกสารเพิ่มเติม</span>
                    </a>
                </li>
                <li>
                    <a href="../login.php" class="dropdown-toggle">
                        <span><i class="fa fa-sign-out-alt"></i> ออกจากระบบ</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    var dropdown = this.nextElementSibling;
                    if (dropdown.style.display === 'block') {
                        dropdown.style.display = 'none';
                    } else {
                        dropdown.style.display = 'block';
                    }
                });
            });
        });
    </script>
</body>

</html>