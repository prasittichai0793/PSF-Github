// สำหรับการคำนวณอายุงาน
function calculateExperienceUserStartDate() {
    const startDateStr = document.getElementById("user_startDate").value;
    if (!startDateStr) return;

    const [day, month, year] = startDateStr.split('/').map(num => parseInt(num));
    const startDate = new Date(year - 543, month - 1, day); // แปลง พ.ศ. เป็น ค.ศ.
    const currentDate = new Date();

    // คำนวณต่างกันระหว่างวัน/เดือน/ปี
    let years = currentDate.getFullYear() - startDate.getFullYear();
    let months = currentDate.getMonth() - startDate.getMonth();
    let days = currentDate.getDate() - startDate.getDate();

    if (days < 0) {
        months -= 1;
        days += new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();
    }
    if (months < 0) {
        years -= 1;
        months += 12;
    }

    // แสดงผลลัพธ์
    document.getElementById("user_exp").value = `${days} วัน ${months} เดือน ${years} ปี`;

    // คำนวณวันลา
    calculateLeaveDays(years, months, days); // เรียกฟังก์ชันคำนวณวันลา
}

// สำหรับการคำนวณวันลา
function calculateLeaveDays(years, months, days) {
    var totalMonths = years * 12 + months; // รวมเดือนทั้งหมด
    var leaveDays = Math.floor(totalMonths * 2); // สามารถลาได้ 2 วันต่อเดือน
    document.getElementById("user_leaveDays").value = `${leaveDays} วัน`; // แสดงผลลัพธ์
}

// สำหรับการคำนวณอายุ
function calculateExperienceUserBirthDate() {
    const birthDateStr = document.getElementById("user_birthDate").value;
    if (!birthDateStr) return;

    const [day, month, year] = birthDateStr.split('/').map(num => parseInt(num));
    const birthDate = new Date(year - 543, month - 1, day); // แปลง พ.ศ. เป็น ค.ศ.
    const currentDate = new Date();

    // คำนวณต่างกันระหว่างวัน/เดือน/ปี
    let years = currentDate.getFullYear() - birthDate.getFullYear();
    let months = currentDate.getMonth() - birthDate.getMonth();
    let days = currentDate.getDate() - birthDate.getDate();

    if (days < 0) {
        months -= 1;
        days += new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();
    }
    if (months < 0) {
        years -= 1;
        months += 12;
    }

    // แสดงผลลัพธ์
    document.getElementById("user_age").value = `${days} วัน ${months} เดือน ${years} ปี`;
}