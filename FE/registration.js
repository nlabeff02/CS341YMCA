document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission
    document.getElementById('signup-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Collect form data
        const firstName = document.getElementById('new-firstname').value.trim();
        const lastName = document.getElementById('new-lastname').value.trim();
        const email = document.getElementById('email').value.trim();
        const phoneNumber = document.getElementById('phone-number').value.trim();
        const newPassword = document.getElementById('new-password').value.trim();
        const confirmPassword = document.getElementById('confirm-password').value.trim();

        // Validate form data
        if (!validateEmail(email)) {
            alert('Please enter a valid email address.');
            return;
        }
        if (!validatePhoneNumber(phoneNumber)) {
            alert('Please enter a valid phone number.');
            return;
        }
        if (!firstName || !lastName) {
            alert('Please enter both your first and last names.');
            return;
        }
        if (newPassword !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }
        if (!newPassword || !confirmPassword || !phoneNumber || !email || !firstName || !lastName) {
            alert('Please fill out all required fields.');
            return;
        }

        // Prepare data to send to the server
        const formData = {
            firstName: firstName,
            lastName: lastName,
            email: email,
            phoneNumber: phoneNumber,
            password: newPassword
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
});
