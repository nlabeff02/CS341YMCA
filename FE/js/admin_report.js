async function getAdminReport() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const includeAllClasses = document.getElementById('includeAllClasses').checked; // Get checkbox value

    if (!startDate || !endDate) {
        alert('Both start and end dates are required.');
        return;
    }

    if (new Date(startDate) > new Date(endDate)) {
        alert('Start date cannot be later than end date.');
        return;
    }

    try {
        const response = await fetch('php/admin_report.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ startDate, endDate, includeAllClasses }), // Include checkbox value
        });

        const result = await response.json();

        if (result.status === 'success') {
            populateAdminReport(result.data);
        } else {
            alert(`Error: ${result.message}`);
        }
    } catch (error) {
        console.error('Error fetching admin report:', error);
        alert('An error occurred while fetching the report.');
    }
}


function populateAdminReport(data) {
    const tableBody = document.getElementById('adminReportTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = ''; // Clear existing rows

    if (data.length === 0) {
        const row = tableBody.insertRow();
        const cell = row.insertCell(0);
        cell.colSpan = 9; // Adjust to match the number of table columns
        cell.textContent = 'No data available for the selected period.';
        return;
    }

    data.forEach(item => {
        const row = tableBody.insertRow();
        createCell(row, item.firstName);
        createCell(row, item.lastName);
        createCell(row, item.email);
        createCell(row, item.className);
        createCell(row, item.startDate);
        createCell(row, item.endDate);
        createCell(row, item.paymentStatus);
    });
}

function createCell(row, value) {
    const cell = row.insertCell();
    cell.textContent = value || '-'; // Default to '-' if value is null or empty
}
