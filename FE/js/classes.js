// classes.js

// Fetches all classes
async function getAllClasses() {
    const data = await fetchData('php/get_all_classes.php');
    if (data.status === 'success' && data.classes) {
        populateClassesTable(data.classes);
    } else {
        console.error('Failed to fetch classes:', data.message);
    }
}

// Fetches future classes and populates the table
async function getFutureClasses() {
    const data = await fetchData('php/getFutureClasses_mgr.php');
    if (data.status === 'success' && data.classes) {
        populateClassesTable(data.classes);
    } else {
        console.error('Failed to fetch classes:', data.message);
    }
}

// Fetches only past classes
function getPastClasses() {
    return fetch('php/get_past_classes.php')
        .then(response => response.json())
        .then(data => {
            // Handle data for past classes
            console.log('Past Classes:', data);
            return data;
        })
        .catch(error => console.error('Error fetching past classes:', error));
}

// Fetches only active classes
function getActiveClasses() {
    return fetch('php/get_active_classes.php')
        .then(response => response.json())
        .then(data => {
            // Handle data for active classes
            console.log('Active Classes:', data);
            return data;
        })
        .catch(error => console.error('Error fetching active classes:', error));
}

// Fetches all classes for a specific member
function getAllMemberClasses(memberId) {
    return fetch(`php/get_all_member_classes.php?memberId=${memberId}`)
        .then(response => response.json())
        .then(data => {
            // Handle data for all classes for a specific member
            console.log(`All Classes for Member ${memberId}:`, data);
            return data;
        })
        .catch(error => console.error(`Error fetching all classes for member ${memberId}:`, error));
}

// Fetches future classes for a specific member
function getFutureMemberClasses(memberId) {
    return fetch(`php/get_future_member_classes.php?memberId=${memberId}`)
        .then(response => response.json())
        .then(data => {
            // Handle data for future classes for a specific member
            console.log(`Future Classes for Member ${memberId}:`, data);
            return data;
        })
        .catch(error => console.error(`Error fetching future classes for member ${memberId}:`, error));
}

// Fetches active classes for a specific member
function getActiveMemberClasses(memberId) {
    return fetch(`php/get_active_member_classes.php?memberId=${memberId}`)
        .then(response => response.json())
        .then(data => {
            // Handle data for active classes for a specific member
            console.log(`Active Classes for Member ${memberId}:`, data);
            return data;
        })
        .catch(error => console.error(`Error fetching active classes for member ${memberId}:`, error));
}

// Fetches past classes for a specific member
function getPastMemberClasses(memberId) {
    return fetch(`php/get_past_member_classes.php?memberId=${memberId}`)
        .then(response => response.json())
        .then(data => {
            // Handle data for past classes for a specific member
            console.log(`Past Classes for Member ${memberId}:`, data);
            return data;
        })
        .catch(error => console.error(`Error fetching past classes for member ${memberId}:`, error));
}

// Fetches future classes for public view.
async function getFutureClassesPublic() {
    const data = await fetchData('php/get_current_future_classes.php');
    if (data.status === 'success' && data.classes) {
        populateClassesTablePublic(data.classes);
    } else {
        console.error('Failed to fetch classes:', data.message);
    }
}

// Reusable Fetch Function
// $url is php endpoint
async function fetchData(url) {
    try {
        const response = await fetch(url);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(`Error fetching data from: ${url}:`, error);
        return { status: 'error', message: error.message };
    }
}


/* * * * * * * * * * * * * * * * * * * *
 *      Tables
 * * * * * * * * * * * * * * * * * * * */
// Creates and populates the tables with all columns.
// Staff view of classes.
function populateClassesTable(classes) {
    const tableBody = document.getElementById('classesTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = ''; // Clear any existing rows

    if (classes.length === 0) {
        createNoDataRow(tableBody, 'No classes available.');
        return;
    }

    classes.forEach(cls => {
        const row = tableBody.insertRow();
        createCell(row, cls.classID);
        createCell(row, cls.className);
        createCell(row, cls.classDescription);
        createCell(row, cls.startDate);
        createCell(row, cls.endDate);
        createCell(row, cls.dayOfWeek);
        createCell(row, cls.startTime);
        createCell(row, cls.endTime);
        createCell(row, cls.classLocation);
        createCell(row, cls.maxParticipants);
        createCell(row, cls.currentParticipantCount) ?? '0'; // Default to zero if no participants.
        createCell(row, cls.priceStaff);          
        createCell(row, cls.priceMember);
        createCell(row, cls.priceNonMember);
        createCell(row, cls.prerequisite ?? 'None');  // Default to 'None' if no prerequisite

        const actionsCell = row.insertCell();
        const modifyButton = createModifyButton(cls);
        actionsCell.appendChild(modifyButton);
    });
}

// Public view of classes.
function populateClassesTablePublic(classes) {
    const tableBody = document.getElementById('classesTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = ''; // Clear any existing rows

    //console.log("isLoggedIn in classes.js:", isLoggedIn); // Debugging line

    if (classes.length === 0) {
        createNoDataRow(tableBody, 'No classes available.');
        return;
    }

    classes.forEach(cls => {
        const row = tableBody.insertRow();
        createCell(row, cls.classID);
        createCell(row, cls.className);
        createCell(row, cls.classDescription);
        createCell(row, cls.startDate);
        createCell(row, cls.endDate);
        createCell(row, cls.dayOfWeek);
        createCell(row, cls.startTime);
        createCell(row, cls.endTime);
        createCell(row, cls.classLocation);
        createCell(row, cls.maxParticipants);
        createCell(row, cls.currentParticipantCount) ?? '0'; // Default to zero if no participants.
        createCell(row, cls.priceMember);
        createCell(row, cls.priceNonMember);
        createCell(row, cls.prerequisiteClassName ?? 'None');  // Default to 'None' if no prerequisite

        const actionsCell = row.insertCell();
        if (isLoggedIn) { // Only show the register button if user is logged in
            const registerButton = createRegisterButton(cls.classID);
            actionsCell.appendChild(registerButton);
        } else {
            actionsCell.innerText = 'Login to Register';
        }
    });
}

// Creates a generic cell and appends it to a row.
function createCell(row, text) {
    const cell = row.insertCell();
    cell.innerText = text || '-';
}


/* * * * * * * * * * * * * * * * * * * *
 *      Buttons
 * * * * * * * * * * * * * * * * * * * */
// Creates a "Modify" button, redirecting to modifyClass-page.php with the class ID if class is not past its end date
function createModifyButton(cls) {
    const modifyButton = document.createElement('button');
    modifyButton.innerText = 'Modify';

    // Convert end date to a Date object and compare it to the current date
    const today = new Date();
    const endDate = new Date(cls.endDate);

    if (endDate < today) {
        modifyButton.disabled = true;
        modifyButton.style.backgroundColor = '#ccc';
        modifyButton.style.cursor = 'not-allowed';
    } else {
        // Redirect to modifyClass-page.php with the class ID as a URL parameter
        modifyButton.onclick = () => {
            window.location.href = `modifyClass-page.php?classID=${cls.classID}`;
        };
    }

    return modifyButton;
}


// Creates a "Register" button, disabling it if the class is full
function createRegisterButton(cls) {
    const registerButton = document.createElement('button');
    registerButton.innerText = 'Register';

    if (cls.currentParticipantCount >= cls.maxParticipants) {
        registerButton.disabled = true;
        registerButton.style.backgroundColor = '#ccc';
        registerButton.style.cursor = 'class full';
    } else {
        registerButton.onclick = () => registerForClass(cls.classID);
    }

    return registerButton;
}