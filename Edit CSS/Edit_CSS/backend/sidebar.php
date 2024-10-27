<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
</head>

<body>
    <div class="sidebar">
        <div class="side-header" onclick="toggleSidebar()">
            <img src="../images/Logo_PSF_BG_White.png" alt="Icon" class="side-header-logo">
            <button>PorSiriyontForklift</button>
            <span class="toggle-arrow"><img src="../icons/Four-burgers.png" alt="toggle-icon"
                    class="toggle-arrow-img-sidebar"></span>
        </div>

        <div class="side-profile">
            <!-- <img src="../images/Logo_PSF_BG_White_Favicon.png" alt="Profile Picture" class="profile-picture"> -->
            <!-- <span class="profile-name">User Name</span> -->
            <span class="profile-name">
                <?php
                session_start(); // เริ่ม session
                if (isset($_SESSION['hr_name'])) {
                    echo $_SESSION['hr_name']; // แสดง hr_name จาก session
                } else {
                    echo "None User"; // ถ้าไม่มี hr_name ใน session จะแสดงข้อความนี้
                }
                ?>
            </span>
        </div>

        <div class="side-content">
            <div class="content-button-for-dropdown" onclick="toggleDropdown(this)">
                <i class="fa fa-users button-icon-for-dropdown"></i>
                <button>รายชื่อพนักงาน</button>
                <span class="dropdown-arrow"><img src="../icons/Down arrow.png" alt="toggle-icon"
                        class="toggle-arrow-img-dropdown"></span>
            </div>

            <div class="dropdown-content">
                <div class="button-in-dropdown" onclick="location.href='show_position1.php'">
                    <i class="fa fa-hard-hat button-icon-in-dropdown"></i>
                    <button>แผนกพนักงานขับรถเครน</button>
                </div>
                <div class="button-in-dropdown" onclick="location.href='show_position2.php'">
                    <i class="fa fa-truck button-icon-in-dropdown"></i>
                    <button>แผนกพนักงานขับรถขนย้าย</button>
                </div>
                <div class="button-in-dropdown" onclick="location.href='show_position3.php'">
                    <i class="fa fa-cogs button-icon-in-dropdown"></i>
                    <button>แผนกพนักงานขับรถโฟล์คลิฟ</button>
                </div>
                <div class="button-in-dropdown" onclick="location.href='show_position4.php'">
                    <i class="fa fa-wrench button-icon-in-dropdown"></i>
                    <button>แผนกช่าง</button>
                </div>
                <div class="button-in-dropdown" onclick="location.href='show_position5.php'">
                    <i class="fa fa-user-tie button-icon-in-dropdown"></i>
                    <button>แผนก HR</button>
                </div>
                <div class="button-in-dropdown" onclick="location.href='show_position6.php'">
                    <i class="fa fa-broom button-icon-in-dropdown"></i>
                    <button>แผนกแม่บ้าน</button>
                </div>
                <div class="button-in-dropdown" onclick="location.href='show_position7.php'">
                    <i class="fa fa-user-shield button-icon-in-dropdown"></i>
                    <button>Admin</button>
                </div>
            </div>

            <div class="content-button" onclick="location.href='show_position.php'">
                <i class="fa fa-briefcase button-icon-content"></i>
                <button>แผนก</button>
            </div>
            <div class="content-button" onclick="location.href='show_personalleave.php'">
                <i class="fa fa-calendar-times button-icon-content"></i>
                <button>แจ้งลากิจ</button>
            </div>
            <div class="content-button" onclick="location.href='show_vacationleave.php'">
                <i class="fa fa-umbrella-beach button-icon-content"></i>
                <button>แจ้งลาพักร้อน</button>
            </div>
            <div class="content-button" onclick="location.href='show_resign.php'">
                <i class="fa fa-user-slash button-icon-content"></i>
                <button>แจ้งลาออก</button>
            </div>
            <div class="content-button" onclick="location.href='show_documents.php'">
                <i class="fa fa-file-upload button-icon-content"></i>
                <button>แจ้งยื่นเอกสาร</button>
            </div>
            <div class="content-button" onclick="location.href='show_timelog.php'">
                <i class="fa fa-user-clock button-icon-content"></i>
                <button>บันทึกเวลาเข้า-ออก</button>
            </div>
            <div class="content-button" onclick="location.href='show_data.php'">
                <i class="fa fa-database button-icon-content"></i>
                <button>Data</button>
            </div>
        </div>

        <div class="side-footer" onclick="logout()">
            <div class="footer-button">
                <i class="fa fa-sign-out-alt button-icon-footer"></i>
                <button class="logout-button" onclick="location.href='../index.php'">Logout</button>
            </div>
        </div>
    </div>

    <script>
        let sidebarOpen = false;

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const toggleArrow = document.querySelector('.toggle-arrow img');
            const displayContainer = document.querySelector('.display-container');

            if (sidebarOpen) {
                sidebar.classList.remove('closed');
                toggleArrow.src = '../icons/Four-burgers.png';
            } else {
                sidebar.classList.add('closed');
                toggleArrow.src = '../icons/Left-arrow.png';
            }
            sidebarOpen = !sidebarOpen;
        }

        // ฟังก์ชันเปิดหรือปิด toggleSidebar
        function checkScreenSize() {
            const toggleArrow = document.querySelector('.side-header');

            if (window.innerWidth >= 931) {
                // ถ้าหน้าจอใหญ่กว่า 931px ปิดการใช้งานการคลิก
                toggleArrow.removeAttribute('onclick');
            } else {
                // ถ้าหน้าจอเล็กกว่า 930px เปิดใช้งานการคลิก
                toggleArrow.setAttribute('onclick', 'toggleSidebar()');
            }
        }

        // เรียกฟังก์ชันเมื่อตอนโหลดหน้า
        window.onload = checkScreenSize;

        // เรียกฟังก์ชันเมื่อตอนหน้าจอเปลี่ยนขนาด
        window.onresize = checkScreenSize;

        let openDropdown = null;

        function toggleDropdown(element) {
            const dropdownContent = element.nextElementSibling;
            const arrow = element.querySelector('.dropdown-arrow');

            if (openDropdown && openDropdown !== dropdownContent) {
                openDropdown.style.display = 'none';
                openDropdown.previousElementSibling.querySelector('.dropdown-arrow').classList.remove('rotate-arrow');
            }

            if (dropdownContent.style.display === 'block') {
                dropdownContent.style.display = 'none';
                arrow.classList.remove('rotate-arrow');
            } else {
                dropdownContent.style.display = 'block';
                arrow.classList.add('rotate-arrow');
                openDropdown = dropdownContent;
            }
        }
    </script>
</body>

</html>