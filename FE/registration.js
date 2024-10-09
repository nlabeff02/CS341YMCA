document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission
    document.getElementById('signup-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Collect form data
        const fullName = document.getElementById('new-username').value.trim();
        const email = document.getElementById('email').value.trim();
        const phoneNumber = document.getElementById('phone-number').value.trim();
        const newPassword = document.getElementById('new-password').value.trim();
        const confirmPassword = document.getElementById('confirm-password').value.trim();
        const hasChild = document.getElementById('has-child').checked;
        const age = document.getElementById('age').value.trim();
        const role = document.getElementById('role').value;
        const permissionID = document.getElementById('permission-id').value;

        // Validate form data
        if (!validateEmail(email)) {
            alert('Please enter a valid email address.');
            return;
        }
        if (!validatePhoneNumber(phoneNumber)) {
            alert('Please enter a valid phone number.');
            return;
        }
        if (!validateFullName(fullName)) {
            alert('Please enter your full name.');
            return;
        }
        if (newPassword !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }
        if (!newPassword || !confirmPassword || !phoneNumber || !email || !fullName || !age || !role || !permissionID) {
            alert('Please fill out all required fields.');
            return;
        }

        // Collect children information if applicable
        let children = [];
        if (hasChild) {
            const childrenInputs = document.querySelectorAll('#children-container .child');
            childrenInputs.forEach(child => {
                const childName = child.querySelector('input[type="text"]').value.trim();
                const childAge = child.querySelector('input[type="number"]').value.trim();
                if (childName && childAge) {
                    children.push({
                        name: childName,
                        age: childAge
                    });
                }
            });
        }

        // Prepare data to send to the server
        const formData = {
            fullName: fullName,
            email: email,
            phoneNumber: phoneNumber,
            password: newPassword,
            hasChild: hasChild,
            children: children,
            age: age,
            role: role,
            permissionID: permissionID
        };

        // Send data to the server using fetch
        fetch('php/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                alert('Registration successful!');
                window.location.href = 'login.html';
            } else {
                alert('Registration failed: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error during registration: ' + error.message);
        });
    });

    // Show/hide children information based on parent checkbox
    document.getElementById('is-parent').addEventListener('change', function() {
        const childrenInfo = document.getElementById('children-info');
        childrenInfo.style.display = this.checked ? 'block' : 'none';
    });

    // Add more child input fields dynamically
    document.getElementById('add-child').addEventListener('click', function() {
        const childrenContainer = document.getElementById('children-container');
        const newChild = document.createElement('div');
        newChild.classList.add('child');
        newChild.innerHTML = `
            <input type="text" placeholder="Child's Name" required>
            <input type="number" placeholder="Child's Age" required>
        `;
        childrenContainer.appendChild(newChild);
    });

    // Email validation function
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Phone number validation function
    function validatePhoneNumber(phoneNumber) {
        const re = /^\d{10}$/;
        return re.test(phoneNumber);
    }

    // Full name validation function
    function validateFullName(fullName) {
        const re = /^[a-zA-Z]+ [a-zA-Z]+$/;
        return re.test(fullName);
    }
});
