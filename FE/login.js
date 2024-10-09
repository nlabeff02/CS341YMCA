document.addEventListener('DOMContentLoaded', function() {
    const state = {
        isLoggedIn: false,
        user: null
    };

    function render() {
        const loggedInDiv = document.getElementById('loggedIn');
        const loginForm = document.getElementById('login-form');
        const errorMessage = document.getElementById('error-message');

        if (state.isLoggedIn) {
            loggedInDiv.style.display = 'block';
            loginForm.style.display = 'none';
            errorMessage.style.display = 'none';
        } else {
            loggedInDiv.style.display = 'none';
            loginForm.style.display = 'block';
        }
    }

    function login() {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const loginButton = document.querySelector('button[type="submit"]');

        if (username.length > 0 && password.length > 0) {
            loginButton.disabled = true;
            loginButton.textContent = 'Logging in...';

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
                    // Redirect to programs.html after successful login
                    window.location.href = '/CS341YMCA/FE/programs.html';
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
    }

    function logout() {
        fetch('/CS341YMCA/FE/php/logout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                state.isLoggedIn = false;
                state.user = null;
                render();
            }
        });
    }

    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();
        login();
    });

    fetch('/CS341YMCA/FE/php/check_login.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.isLoggedIn) {
            state.isLoggedIn = true;
            state.user = data;
        }
        render();
    });
});
