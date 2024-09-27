document.getElementById('applicationForm').addEventListener('submit', function(event) {
    const fullName = document.getElementById('fullName').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();

    if (fullName === '' || email === '' || phone === '') {
        alert('Please fill out all required fields.');
        event.preventDefault();
        return;
    }

    alert('Form is being submitted...');
});
function printPDF() {
    const form = document.getElementById('applicationForm');
    
    // Create a new window
    const printWindow = window.open('', '', 'height=600,width=800');

    // Write form data to the new window
    printWindow.document.write('<html><head><title>Application Form</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
    printWindow.document.write('table, th, td { border: 1px solid black; padding: 10px; }');
    printWindow.document.write('th { background-color: #f2f2f2; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h2>Application Form</h2>');
    printWindow.document.write('<table>');

    // Loop through form fields and print each one
    for (let i = 0; i < form.elements.length; i++) {
        const element = form.elements[i];
        if (element.type !== 'submit' && element.type !== 'button' && element.name) {
            printWindow.document.write('<tr><th>' + element.name + '</th><td>' + element.value + '</td></tr>');
        }
    }

    printWindow.document.write('</table>');
    printWindow.document.write('</body></html>');

    // Close the document and trigger the print dialog
    printWindow.document.close();
    printWindow.print();
}
