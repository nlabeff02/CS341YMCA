document.addEventListener('DOMContentLoaded', function() {
    // Signup form submission
    document.getElementById('signup-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const newUsername = document.getElementById('new-username').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        const phoneNumber = document.getElementById('phone-number').value;
        const email = document.getElementById('email').value;
        const fullName = document.getElementById('new-username').value;

        // Perform validation
        if (!validateEmail(email)) {
            alert('Please enter a valid email address.');
            return;
        }

        if (!validatePhoneNumber(phoneNumber)) {
            alert('Please enter a valid phone number with the correct number of digits.');
            return;
        }

        if (!validateFullName(fullName)) {
            alert('Please enter your full name (first and last name).');
            return;
        }

        if (newPassword !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }

        if (newUsername && newPassword && confirmPassword && phoneNumber && email && fullName) {
            alert('Signup successful!');
        } else {
            alert('Please fill out all required fields.');
        }
    });

    // Show/hide children info based on parent checkbox
    document.getElementById('is-parent').addEventListener('change', function() {
        const childrenInfo = document.getElementById('children-info');
        if (this.checked) {
            childrenInfo.style.display = 'block';
        } else {
            childrenInfo.style.display = 'none';
        }
    });

    // Add another child input fields
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
