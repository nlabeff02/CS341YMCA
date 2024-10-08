document.addEventListener('DOMContentLoaded', function() {
    // Login form submission
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const loginButton = this.querySelector('button[type="submit"]');

        // Perform basic validation
        if (username.length > 0 && password.length > 0) {
            loginButton.disabled = true;
            loginButton.textContent = 'Logging in...';

            // Prepare the data
            const formData = {
                username: username,
                password: password
            };

            fetch('/CS341YMCA/FE/php/login.php', {
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
                loginButton.disabled = false;
                loginButton.textContent = 'Login';

                if (data.status === 'success') {
                    window.location.href = 'dashboard.php';
                } else {
                    document.getElementById('error-message').textContent = data.message;
                    document.getElementById('error-message').style.display = 'block';
                }
            })
            .catch(error => {
                loginButton.disabled = false;
                loginButton.textContent = 'Login';
                document.getElementById('error-message').textContent = 'Error during login: ' + error.message;
                document.getElementById('error-message').style.display = 'block';
            });
        } else {
            alert('Please enter both username and password.');
        }
    });

    // The signup form submission is no longer relevant here as per your updates
});
