function printPage() {
    // Get the page title
    var pageTitle = document.querySelector('h1').textContent;
    var currentDate = new Date().toLocaleDateString('th-TH', { day: '2-digit', month: '2-digit', year: 'numeric' });

    // Create a new window for printing
    var printWindow = window.open('', '', 'height=842,width=595'); // A4 size in pixels (approx)
    printWindow.document.write('<html><head><title>' + pageTitle + '</title>');
    printWindow.document.write('<link rel="stylesheet" href="print.css">'); // Link to print.css
    printWindow.document.write('<style>@media print { body { font-family: Arial, sans-serif; margin: 0; padding: 0; } .table { width: 100%; border-collapse: collapse; } .table th, .table td { border: 1px solid #000; padding: 5px; text-align: left; } .header-print { text-align: center; margin-bottom: 10mm; } .actions, .btn { display: none; } }</style>');
    printWindow.document.write('</head><body>');
    
    printWindow.document.write('<div class="header-print">');
    printWindow.document.write('<h2>บริษัท ป.ศิริยนต์โฟล์คลิฟ จำกัด</h2>');
    printWindow.document.write('<p>วันที่: ' + currentDate + '</p>'); // Corrected date format
    printWindow.document.write('</div>');

    var content = document.querySelector('.main-content').innerHTML;
    content = content.replace(/<div class="actions">.*?<\/div>/s, ''); // Remove actions
    printWindow.document.write(content);

    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}