function cancelForm() {
    // ล้างค่าฟอร์ม
    document.querySelector("form").reset();

    // เปลี่ยนเส้นทางไปยัง test.php
    window.location.href = 'show_position1.php';
}