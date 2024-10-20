<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- <link rel="stylesheet" href="css/sidebar.css"> -->
    <style>
        body {
            margin: 0;
            font-family: Verdana, sans-serif;
            background-color: #FFDEDE;
        }

        .sidebar {
            position: relative;
            background-color: #FFB8B8;
            height: 100vh;
            width: 250px;
            display: flex;
            flex-direction: column;
            transition: width 0.3s ease;
            z-index: 2;
        }

        .side-header {
            position: relative;
            background-color: #FFB8B8;
            height: 50px;
            padding: 0 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
            border-bottom: 1px solid #ffffff;
        }

        .side-header-logo {
            width: 25px;
            height: 25px;
            margin-right: 5px;
        }

        .side-header button {
            height: 40px;
            width: 100%;
            font-size: 16px;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
        }

        .toggle-arrow-img-sidebar {
            width: 15px;
            height: 15px;
            margin-left: 5px;
            filter: invert(100%) brightness(100%);
        }

        .toggle-arrow {
            display: none;
        }

        .side-profile {
            position: relative;
            width: auto;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
            background-color: #FFB8B8;
            border-bottom: 1px solid #ffffff;
        }

        .profile-picture {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #000000;
        }

        .profile-name {
            margin-left: 10px;
        }

        .side-content {
            margin: -16px 0;
            padding: 16px 0;
            position: relative;
            flex: 1;
            direction: rtl;
            overflow-x: hidden;
        }

        .content-button-for-dropdown,
        .button-in-dropdown,
        .content-button,
        .footer-button {
            position: relative;
            padding: 0px 5px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            direction: ltr;
            cursor: pointer;
            z-index: 1;
            background-color: #FFB8B8;
        }

        .content-button-for-dropdown button,
        .button-in-dropdown button,
        .content-button button {
            height: 40px;
            width: 100%;
            font-size: 16px;
            margin: 0px 0px 0px 5px;
            cursor: pointer;
            align-items: center;
            background: none;
            border: none;
            text-align: left;
            padding-left: 10px;
        }

        .footer-button button {
            height: 40px;
            width: 100%;
            font-size: 16px;
            margin: 0px 0px 0px 5px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
        }

        .content-button-for-dropdown::after,
        .button-in-dropdown::after,
        .content-button::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 90%;
            height: 1px;
            background-color: white;
            transform: translateX(-50%);
        }

        .button-icon-for-dropdown {
            font-size: 20px;
            color: white;
            display: flex;
            align-items: center;
        }

        .toggle-arrow-img-dropdown {
            width: 15px;
            height: 15px;
            filter: invert(100%) brightness(100%);
        }

        .rotate-arrow {
            transform: rotate(-90deg);
        }

        .dropdown-content {
            display: none;
            flex-direction: column;
            padding-left: 16px;
        }

        .button-icon-in-dropdown,
        .button-icon-content,
        .button-icon-footer {
            font-size: 20px;
            color: white;
        }

        .side-footer {
            position: relative;
            width: auto;
            height: 50px;
            background-color: #1ed24b;
            display: flex;
            flex-direction: column;
            z-index: 2;
            border-top: 1px solid #ffffff;
        }

        @media screen and (max-width: 930px) {
            .sidebar {
                position: relative;
                background-color: #FFB8B8;
                height: 100vh;
                width: 60px;
                display: flex;
                flex-direction: column;
                transition: width 0.3s ease;
                z-index: 2;
            }

            .toggle-arrow {
                display: inline-block;
            }

            .sidebar.closed {
                width: 250px;
            }

            .side-header button,
            .profile-name,
            .content-button-for-dropdown button,
            .button-in-dropdown button,
            .content-button button,
            .footer-button button {
                display: none;
            }

            .sidebar.closed .side-header button,
            .sidebar.closed .profile-name,
            .sidebar.closed .content-button-for-dropdown button,
            .sidebar.closed .button-in-dropdown button,
            .sidebar.closed .content-button button,
            .sidebar.closed .footer-button button {
                display: flex;
            }

            .side-content {
                margin: -16px 0;
                padding: 16px 0;
                position: relative;
                flex: 1;
                direction: rtl;
                overflow-x: hidden;
            }
        }
    </style>
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
            <span class="profile-name">User Name</span>
        </div>

        <div class="side-content">
            <div class="content-button-for-dropdown" onclick="toggleDropdown(this)">
                <i class="fa fa-users button-icon-for-dropdown"></i>
                <button>รายชื่อพนักงาน</button>
                <span class="dropdown-arrow"><img src="../icons/Down arrow.png" alt="toggle-icon"
                        class="toggle-arrow-img-dropdown"></span>
            </div>

            <div class="dropdown-content">
                <div class="button-in-dropdown">
                    <i class="fa fa-hard-hat button-icon-in-dropdown"></i>
                    <button onclick="location.href='show_position1.php'">แผนกพนักงานขับรถเครน</button>
                </div>
                <div class="button-in-dropdown">
                    <i class="fa fa-truck button-icon-in-dropdown"></i>
                    <button onclick="location.href='show_position2.php'">แผนกพนักงานขับรถขนย้าย</button>
                </div>
                <div class="button-in-dropdown">
                    <i class="fa fa-cogs button-icon-in-dropdown"></i>
                    <button onclick="location.href='show_position3.php'">แผนกพนักงานขับรถโฟล์คลิฟ</button>
                </div>
                <div class="button-in-dropdown">
                    <i class="fa fa-wrench button-icon-in-dropdown"></i>
                    <button onclick="location.href='show_position4.php'">แผนกช่าง</button>
                </div>
                <div class="button-in-dropdown">
                    <i class="fa fa-user-tie button-icon-in-dropdown"></i>
                    <button onclick="location.href='show_position5.php'">แผนก HR</button>
                </div>
                <div class="button-in-dropdown">
                    <i class="fa fa-broom button-icon-in-dropdown"></i>
                    <button onclick="location.href='show_position6.php'">แผนกแม่บ้าน</button>
                </div>
                <div class="button-in-dropdown">
                    <i class="fa fa-user-shield button-icon-in-dropdown"></i>
                    <button onclick="location.href='show_position7.php'">Admin</button>
                </div>
            </div>

            <div class="content-button">
                <i class="fa fa-briefcase button-icon-content"></i>
                <button onclick="location.href='show_position.php'">แผนก</button>
            </div>
            <div class="content-button">
                <i class="fa fa-calendar-times button-icon-content"></i>
                <button>แจ้งลากิจ</button>
            </div>
            <div class="content-button">
                <i class="fa fa-umbrella-beach button-icon-content"></i>
                <button>แจ้งลาพักร้อน</button>
            </div>
            <div class="content-button">
                <i class="fa fa-user-slash button-icon-content"></i>
                <button>แจ้งลาออก</button>
            </div>
            <div class="content-button">
                <i class="fa fa-file-upload button-icon-content"></i>
                <button>แจ้งยื่นเอกสาร</button>
            </div>
            <div class="content-button">
                <i class="fa fa-user-clock button-icon-content"></i>
                <button>บันทึกเวลาเข้า-ออก</button>
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