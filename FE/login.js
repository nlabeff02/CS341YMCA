document.addEventListener('DOMContentLoaded', function() {
    // Login form submission
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const username = document.getElementById('username').value.trim;
        const password = document.getElementById('password').value.trim;
        const loginButton = this.querySelector('button[type="submit"]');

        // Perform basic validation
        if (username && password) {
            // Prepare the data
            const formData = {
                username: username,
                password: password
            };
            fetch('php/login.php', {
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
                    console.log('Login Successful:', data);
                    window.location.href = 'dashboard.php';
                } else {
                    alert('Login Failed: ' + data.message);
                }
            })
            .catch(error => {
                loginButton.disabled = false;
                loginButton.textContent = 'Login';
                alert('Error occurred during login: ' + error.message);
            });
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
