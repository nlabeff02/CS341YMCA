document.addEventListener('DOMContentLoaded', function() {
    // Login form submission
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Perform basic validation
        if (username && password) {
            alert('Login successful!');
        } else {
            alert('Please enter both username and password.');
        }
    });

    // Signup form submission
    document.getElementById('signup-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const newUsername = document.getElementById('new-username').value;
        const newPassword = document.getElementById('new-password').value;

        // Perform basic validation
        if (newUsername && newPassword) {
            alert('Signup successful!');
        } else {
            alert('Please enter both username and password.');
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
});
