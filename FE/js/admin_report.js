// Asynchronous function to fetch the admin report data
async function getAdminReport() {
    // Get values from input fields
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const includeAllClasses = document.getElementById('includeAllClasses').checked; // Get checkbox value

    // Validate that both dates are provided
    if (!startDate || !endDate) {
        alert('Both start and end dates are required.');
        return;
    }

    // Validate that the start date is not later than the end date
    if (new Date(startDate) > new Date(endDate)) {
        alert('Start date cannot be later than end date.');
        return;
    }

    try {
        // Send POST request to the backend with the dates and checkbox value
        const response = await fetch('php/admin_report.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ startDate, endDate, includeAllClasses }), // Include checkbox value in request
        });

        // Parse the JSON response
        const result = await response.json();

        // Handle success or error based on the response
        if (result.status === 'success') {
            populateAdminReport(result.data); // Populate the report table with fetched data
        } else {
            alert(`Error: ${result.message}`); // Display error message
        }
    } catch (error) {
        // Log and alert if there's a fetch error
        console.error('Error fetching admin report:', error);
        alert('An error occurred while fetching the report.');
    }
}

// Function to populate the admin report table with fetched data
function populateAdminReport(data) {
    const tableBody = document.getElementById('adminReportTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = ''; // Clear existing rows in the table

    // If no data, display a message in the table
    if (data.length === 0) {
        const row = tableBody.insertRow();
        const cell = row.insertCell(0);
        cell.colSpan = 9; // Adjust to match the number of table columns
        cell.textContent = 'No data available for the selected period.';
        return;
    }

    // Loop through data and create rows for each item
    data.forEach(item => {
        const row = tableBody.insertRow();
        createCell(row, item.firstName); // First Name
        createCell(row, item.lastName); // Last Name
        createCell(row, item.email); // Email
        createCell(row, item.className); // Class Name
        createCell(row, item.startDate); // Start Date
        createCell(row, item.endDate); // End Date
        createCell(row, item.paymentStatus); // Payment Status
    });
}

// Utility function to create and populate a table cell
function createCell(row, value) {
    const cell = row.insertCell(); // Create a new cell
    cell.textContent = value || '-'; // Populate cell with value or default to '-'
}
