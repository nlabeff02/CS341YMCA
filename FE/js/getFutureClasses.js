// js/getClass.js

async function fetchClasses() {
    try {
        const response = await fetch('php/getFutureClasses_mgr.php');
        const data = await response.json();

        if (data.status === 'success' && data.classes) {
            console.log(data.classes); // Debugging output to check class data
            populateClassesTable(data.classes);
        } else {
            console.error('Failed to fetch classes:', data.message);
        }
    } catch (error) {
        console.error('Error fetching classes:', error);
    }
}

function populateClassesTable(classes) {
    const tableBody = document.getElementById('classesTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = ''; // Clear any existing rows

    if (classes.length === 0) {
        const row = tableBody.insertRow();
        const cell = row.insertCell(0);
        cell.colSpan = 10;
        cell.innerText = "No upcoming classes available.";
        cell.style.textAlign = 'center';
        return;
    }

    classes.forEach(cls => {
        const row = tableBody.insertRow();

        row.insertCell(0).innerText = cls.className;
        row.insertCell(1).innerText = cls.startDate;
        row.insertCell(2).innerText = cls.endDate;
        row.insertCell(3).innerText = cls.startTime;
        row.insertCell(4).innerText = cls.endTime;
        row.insertCell(5).innerText = cls.maxParticipants;
        row.insertCell(6).innerText = cls.currentEnrolled;
        row.insertCell(7).innerText = cls.priceMember;
        row.insertCell(8).innerText = cls.priceNonMember;
        row.insertCell(9).innerText = cls.prerequisite;

        // Create the Register button
        const registerCell = row.insertCell(10);
        const registerButton = document.createElement('button');
        registerButton.innerText = 'Register';

        // Check if the class is full
        if (cls.currentEnrolled >= cls.maxParticipants) {
            registerButton.disabled = true;
            registerButton.style.backgroundColor = '#ccc'; // Greyed out style
            registerButton.style.cursor = 'not-allowed';
        } else {
            registerButton.onclick = () => registerForClass(cls.className);
        }

        registerCell.appendChild(registerButton);
    });
}

// Function to handle class registration logic
function registerForClass(className) {
    alert(`Registering for ${className}`);
    // Add your registration logic here (e.g., send a request to the server)
}

// Fetch and populate the table on page load
document.addEventListener('DOMContentLoaded', fetchClasses);
