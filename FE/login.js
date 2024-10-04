document.addEventListener('DOMContentLoaded', function() {
    // Login form submission
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Perform basic validation
        if (username && password) {
            // Here you can add your login logic, e.g., sending data to the server
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
            // Here you can add your signup logic, e.g., sending data to the server
            alert('Signup successful!');
        } else {
            alert('Please enter both username and password.');
        }
    });
});
