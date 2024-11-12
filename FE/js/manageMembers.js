// Function to search for members based on form input
function searchMembers() {
    const searchType = document.getElementById("searchType").value;
    const searchText = document.getElementById("searchText").value;

    fetch('php/manageMembers_mgr.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action: 'search',
            searchType: searchType,
            searchText: searchText
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            populateResultsTable(data.members);
        } else {
            console.error(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to view all members (without search criteria)
function viewAllMembers() {
    fetch('php/manageMembers_mgr.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'viewAll' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            populateResultsTable(data.members);
        } else {
            console.error(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}


// Function to populate the results table with member data
function populateResultsTable(members) {
    const tableBody = document.getElementById("resultsTable").getElementsByTagName("tbody")[0];
    tableBody.innerHTML = ''; // Clear existing rows

    members.forEach(member => {
        const row = tableBody.insertRow();
        row.insertCell(0).innerText = member.memberId;
        row.insertCell(1).innerText = member.firstName;
        row.insertCell(2).innerText = member.lastName;
        row.insertCell(3).innerText = member.email;
        row.insertCell(4).innerText = member.phone;
        row.insertCell(5).innerText = member.role;

        // Action cell for Edit and View Registrations buttons
        const actionCell = row.insertCell(6);

        // Edit button
        const editButton = document.createElement("button");
        editButton.innerText = "Edit";
        editButton.onclick = () => editMember(member);
        actionCell.appendChild(editButton);

        // View Registrations button
        const viewRegistrationsButton = document.createElement("button");
        viewRegistrationsButton.innerText = "View Registrations";
        viewRegistrationsButton.onclick = () => viewRegistrations(member.memberId);
        actionCell.appendChild(viewRegistrationsButton);
    });
}


// Function to open the edit form and populate it with selected member data
function editMember(member) {
    document.getElementById("editMemberId").value = member.memberId;
    document.getElementById("editFirstName").value = member.firstName;
    document.getElementById("editLastName").value = member.lastName;
    document.getElementById("editEmail").value = member.email;
    document.getElementById("editPhone").value = member.phone;
    document.getElementById("editRole").value = member.role;

    // Show the edit form
    document.getElementById("editFormContainer").style.display = "block";
}

// Function to save the edited member details
function saveMember() {
    const memberId = document.getElementById("editMemberId").value;
    const firstName = document.getElementById("editFirstName").value;
    const lastName = document.getElementById("editLastName").value;
    const email = document.getElementById("editEmail").value;
    const phone = document.getElementById("editPhone").value;
    const role = document.getElementById("editRole").value;

    fetch('php/manageMembers_mgr.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action: 'save',
            memberId: memberId,
            firstName: firstName,
            lastName: lastName,
            email: email,
            phone: phone,
            role: role
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            console.log(data.message);
            cancelEdit();
            viewAllMembers(); // Refresh the table to show updated information
        } else {
            console.error(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to cancel editing
function cancelEdit() {
    document.getElementById("editFormContainer").style.display = "none";
}

// Function to populate the results table with member data
function populateResultsTable(members) {
    const tableBody = document.getElementById("resultsTable").getElementsByTagName("tbody")[0];
    tableBody.innerHTML = ''; // Clear existing rows

    members.forEach(member => {
        const row = tableBody.insertRow();
        row.insertCell(0).innerText = member.memberId;
        row.insertCell(1).innerText = member.firstName;
        row.insertCell(2).innerText = member.lastName;
        row.insertCell(3).innerText = member.email;
        row.insertCell(4).innerText = member.phone;
        row.insertCell(5).innerText = member.role;

        // Action cell for Edit and View Registrations buttons
        const actionCell = row.insertCell(6);

        // Edit button
        const editButton = document.createElement("button");
        editButton.innerText = "Edit";
        editButton.onclick = () => editMember(member);
        editButton.classList.add("button-spacing"); // Adds class for spacing
        actionCell.appendChild(editButton);


        // View Registrations button
        const viewRegistrationsButton = document.createElement("button");
        viewRegistrationsButton.innerText = "View Registrations";
        viewRegistrationsButton.onclick = () => viewRegistrations(member.memberId);
        actionCell.appendChild(viewRegistrationsButton);
    });
}

function viewRegistrations(memberId) {
    fetch('php/manageMembers_mgr.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'getRegistrations', memberId: memberId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            displayRegistrations(data.registrations);
        } else {
            console.error(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function displayRegistrations(registrations) {
    const tableBody = document.getElementById("registrationsTableBody");
    tableBody.innerHTML = ''; // Clear previous data

    console.log("Registrations received:", registrations);

    registrations.forEach(registration => {
        const row = tableBody.insertRow();
        row.insertCell(0).innerText = registration.className;
        row.insertCell(1).innerText = registration.startDate;
        row.insertCell(2).innerText = registration.endDate;
        row.insertCell(3).innerText = registration.paymentStatus;
    });

    document.getElementById("registrationsContainer").style.display = "block";
}


function closeRegistrationsModal() {
    document.getElementById("registrationsContainer").style.display = "none";
}

