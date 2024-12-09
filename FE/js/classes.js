// classes.js

function searchClasses() {
    const searchType = document.getElementById("classSearchType").value;
    const searchText = document.getElementById("classSearchText").value;

    // Send POST request to the server to search for classes
    fetch('php/get_current_future_classes.php', {
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
            populateClassesTablePublic(data.classes); // Use your existing function
        } else {
            console.error(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function searchManageClasses() {
    const searchType = document.getElementById("classSearchType").value;
    const searchText = document.getElementById("classSearchText").value;

    // Send POST request to the server to search for classes
    fetch('php/get_all_classes.php', {
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
            populateClassesTable(data.classes); // Use your existing function
        } else {
            console.error(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Fetches all classes
async function getAllClasses() {
    const data = await fetchData('php/get_all_classes.php', { action: 'viewAll' });
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
async function getMemberClassesActive(memberId) {
 /*   return fetch(`php/get_member_classes_active.php?memberId=${memberId}`)
        .then(response => response.json())
        .then(data => {
            // Handle data for active classes for a specific member
            console.log(`Active Classes for Member ${memberId}:`, data);
            return data;
        })
        .catch(error => console.error(`Error fetching active classes for member ${memberId}:`, error));
        */
    const data = await fetchData(`php/get_member_classes_active.php?memberId=${memberId}`);
    if (data.status === 'success' && data.classes) {
        populateMemberClassesActive(data.classes);
    } else {
        console.error('Failed to fetch classes:', data.message);
    }
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
    try {
        // Pass the action parameter to the server
        const data = await fetchData('php/get_current_future_classes.php', {
            action: 'viewAll' // Action to fetch all future classes
        });

        if (data.status === 'success' && data.classes) {
            populateClassesTablePublic(data.classes); // Use your existing function
        } else {
            console.error('Failed to fetch classes:', data.message);
        }
    } catch (error) {
        console.error('Error fetching classes:', error);
    }
}


async function registerForClass(cls) {
    console.log(`Class ID: ${cls}, Person ID: ${personID}, Person Role: ${personRole}`);
    try {
        const response = await fetch('php/registerClass.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                classID: cls,
                personID: personID,
                personRole: personRole
            })
        });

        const data = await response.json();

        // Check the status from the PHP response
        if (data.status === 'success') {
            console.log('Registration successful:', data.message);
            alert('Registration successful! Redirecting to your registered classes...');
            // Redirect to members.php on successful registration
            window.location.href = 'members.php';
        } else if (data.message === 'You are already registered for this class.') {
            console.log('You are already registered for this class.');
            alert('You are already registered for this class.');
        } else if (data.message === 'You must complete the prerequisite class before registering for this class.') {
            console.log('Prerequisite not completed.');
            alert('You must complete the prerequisite class before registering for this class.');
        } else {
            console.error('Registration failed:', data.message);
            alert('Registration failed: ' + data.message);
        }
    } catch (error) {
        console.error('Error during registration:', error);
        alert('An error occurred. Please try again.');
    }
}


async function cancelMyRegistration(cls) {
    // Display confirmation popup
    const cancelRegistrationPopup = confirm(
        "Are you sure you want to cancel your registration for\n\n" +
        `${cls.className}?\n\n` +
        "You are responsible for collecting refunds from the YMCA front desk."
    );

    if (cancelRegistrationPopup) {
        // Variables to pass to the server
        const data = { classID: cls.classID, personID: personID };

        try {
            // Send POST request to the server
            const response = await fetch('php/cancel_my_registration_mgr.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            });

            // Handle server response
            if (!response.ok) {
                throw new Error('Failed to cancel registration.');
            }

            const result = await response.json();
            if (result.status === 'success') {
                alert("You have successfully cancelled your registration.\n\nCheck with the Front Desk for refunds.");
            } else {
                alert(`Error: ${result.message}`);
            }
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred while cancelling your registration. Please try again later.");
        }
    } else {
        console.log("Registration was NOT cancelled.");
    }
}

// Reusable Fetch Function
// $url is the PHP endpoint and $params is an optional object containing parameters to send
async function fetchData(url, params = {}) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(params) // Send params as form data
        });

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
        createCell(row, cls.currentParticipantCount ?? '0'); // Default to zero if no participants.
        createCell(row, cls.priceStaff);          
        createCell(row, cls.priceMember);
        createCell(row, cls.priceNonMember);
        createCell(row, cls.prerequisiteClassName ?? 'None');  // Default to 'None' if no prerequisite
        createCell(row, cls.isActive);

        const actionsCell = row.insertCell();
        
        // Add Modify Button
        const modifyButton = createModifyButton(cls);
        actionsCell.appendChild(modifyButton);

        // Add Cancel Button (Red)
        const cancelButton = createCancelButton(cls);
        actionsCell.appendChild(cancelButton);
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
            const registerButton = createRegisterButton(cls);
            actionsCell.appendChild(registerButton);
        } else {
            actionsCell.innerText = 'Login to Register';
        }
    });
}

function populateMemberClassesActive(classes) {
    const tableBody = document.getElementById('classesTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = ''; // Clear any existing rows

    //console.log("isLoggedIn in classes.js:", isLoggedIn); // Debugging line

    if (classes.length === 0) {
        createNoDataRow(tableBody, 'No classes available.');
        return;
    }

    classes.forEach(cls => {
        const row = tableBody.insertRow();
        //createCell(row, cls.classID);
        createCell(row, cls.className);
        //createCell(row, cls.classDescription);
        createCell(row, cls.startDate);
        createCell(row, cls.endDate);
        createCell(row, cls.dayOfWeek);
        createCell(row, cls.startTime);
        createCell(row, cls.endTime);
        createCell(row, cls.classLocation);
        //createCell(row, cls.maxParticipants);
        //createCell(row, cls.currentParticipantCount) ?? '0'; // Default to zero if no participants.
        //createCell(row, cls.priceMember);
        //createCell(row, cls.priceNonMember);
        //createCell(row, cls.prerequisiteClassName ?? 'None');  // Default to 'None' if no prerequisite
        createCell(row, cls.paymentStatus);
        //createCell(row, cls.regIsActive);

        const actionsCell = row.insertCell();
        if (cls.isActive) {
            const cancelMyRegistrationButton = createCancelMyRegistrationButton(cls);
            actionsCell.appendChild(cancelMyRegistrationButton);
        } else {
            actionsCell.innerText = 'Class is CANCELLED';
            actionsCell.style.backgroundColor = 'red';
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


// Creates a "Cancel My Registration" button
function createCancelMyRegistrationButton(cls) {
    const cancelMyRegistrationButton = document.createElement('button');
    cancelMyRegistrationButton.innerText = 'Cancel My Registration';
    cancelMyRegistrationButton.onclick = () => cancelMyRegistration(cls);
    return cancelMyRegistrationButton;
}


function createCancelButton(cls) {
    const cancelButton = document.createElement('button');
    cancelButton.innerText = 'Cancel';
    cancelButton.style.backgroundColor = 'red';
    cancelButton.style.color = 'white';

    // Convert end date to a Date object and compare it to the current date
    const today = new Date();
    const endDate = new Date(cls.endDate);

    if (endDate < today || cls.isActive === '0') { // Disable if class has already ended or is inactive
        cancelButton.disabled = true;
        cancelButton.style.backgroundColor = '#ccc';
        cancelButton.style.cursor = 'not-allowed';
    } else {
        // Prompt for cancellation and send the request to cancel the class for everyone
        cancelButton.onclick = () => {
            if (confirm('Are you sure you want to cancel this class for all registered users?')) {
                // Send the cancel request to the backend
                fetch('php/cancelClass.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        classID: cls.classID,
                        personID: personID // The ID of the staff member who is requesting the cancellation
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Class canceled successfully for all registered users');
                        // Optionally, refresh the table or remove the canceled class from the view
                        location.reload(); // Reload the page or refresh the table dynamically
                    } else {
                        alert('Error canceling class: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
            }
        };
    }

    return cancelButton;
}



