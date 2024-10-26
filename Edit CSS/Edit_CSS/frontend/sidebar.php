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
            <img src="../images/Logo_PSF_BG_White_Favicon.png" alt="Profile Picture" class="profile-picture">
            <!-- <span class="profile-name">User Name</span> -->
            <span class="profile-name">
                <?php
                session_start(); // เริ่ม session
                if (isset($_SESSION['user_name'])) {
                    echo $_SESSION['user_name']; // แสดง hr_name จาก session
                } else {
                    echo "None User"; // ถ้าไม่มี hr_name ใน session จะแสดงข้อความนี้
                }
                ?>
            </span>
        </div>

        <div class="side-content">
            <div class="content-button" onclick="location.href='show_profile.php'">
                <i class="fa fa-briefcase button-icon-content"></i>
                <button>ข้อมูลส่วนตัว</button>
            </div>
            <div class="content-button" onclick="location.href='show_history.php'">
                <i class="fa fa-calendar-times button-icon-content"></i>
                <button>ประวัติ</button>
            </div>
            <div class="content-button" onclick="location.href='personalleave.php'">
                <i class="fa-solid fa-window-restore button-icon-content"></i>
                <button>ลากิจ</button>
            </div>
            <div class="content-button" onclick="location.href='vacationleave.php'">
                <i class="fa fa-umbrella-beach button-icon-content"></i>
                <button>ลาพักร้อน</button>
            </div>
            <div class="content-button" onclick="location.href='resign.php'">
                <i class="fa fa-user-slash button-icon-content"></i>
                <button>ลาออก</button>
            </div>
            <div class="content-button" onclick="location.href='documents.php'">
                <i class="fa fa-user-slash button-icon-content"></i>
                <button>ยื่นเอกสารเพิ่มเติม</button>
            </div>
        </div>

        <div class="side-footer" onclick="location.href='../index.php'">
            <div class="footer-button">
                <i class="fa fa-sign-out-alt button-icon-footer"></i>
                <button class="logout-button">Logout</button>
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
    </script>
</body>

</html>