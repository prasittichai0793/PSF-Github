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
                    <a href="#" class="dropdown-toggle">
                        <span><i class="fa fa-users"></i> รายชื่อพนักงาน</span>
                        <i class="fa fa-caret-down arrow"></i>
                    </a>
                    <ul class="dropdown">
                        <li><a href="show_position1.php"><i class="fa fa-hard-hat"></i> แผนกพนักงานขับรถเครน</a></li>
                        <li><a href="show_position2.php"><i class="fa fa-truck"></i> แผนกพนักงานขับรถขนย้าย</a></li>
                        <li><a href="show_position3.php"><i class="fa fa-cogs"></i> แผนกพนักงานขับรถโฟล์คลิฟ</a></li>
                        <li><a href="show_position4.php"><i class="fa fa-wrench"></i> แผนกช่าง</a></li>
                        <li><a href="show_position5.php"><i class="fa fa-user-tie"></i> แผนก HR</a></li>
                        <li><a href="show_position6.php"><i class="fa fa-broom"></i> แผนกแม่บ้าน</a></li>
                        <li><a href="show_position7.php"><i class="fa fa-user-shield"></i> Admin</a></li>
                    </ul>
                </li>
                <li>
                    <a href="show_position.php" class="dropdown-toggle">
                        <span><i class="fa fa-briefcase"></i> แผนก</span>
                    </a>
                </li>
                <li>
                    <a href="show_personalleave.php" class="dropdown-toggle">
                        <span><i class="fa fa-calendar-times"></i> แจ้งลากิจ</span>
                    </a>
                </li>
                <li>
                    <a href="show_vacationleave.php" class="dropdown-toggle">
                        <span><i class="fa fa-umbrella-beach"></i> แจ้งลาพักร้อน</span>
                    </a>
                </li>
                <li>
                    <a href="show_resign.php" class="dropdown-toggle">
                        <span><i class="fa fa-user-slash"></i> แจ้งลาออก</span>
                    </a>
                </li>

                <li>
                    <a href="show_documents.php" class="dropdown-toggle">
                        <span><i class="fa fa-file-upload"></i> แจ้งยื่นเอกสาร</span>
                    </a>
                </li>
                <li>
                    <a href="show_position.php" class="dropdown-toggle">
                        <span><i class="fa fa-user-clock"></i> บันทึกเวลาเข้า-ออก</span>
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