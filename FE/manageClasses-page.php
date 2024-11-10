
<!DOCTYPE html>
<html lang="en">
<head>
    <title>YMCA | Events and Programs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/ymca-main.css" />
</head>
<body>
    <?php include 'php/nav_mgr.php'; ?>
    <img src="img/Designer.png" alt="YMCA" width="200" height="200">
    <h1>Classes</h1>

    <div id="page-container">
        <!-- Left Column (New Class Form) -->
        <div id="left-column">
            <div id="class-form">
                <h2>Create New Class</h2>
                <form id="create-class-form">
                    <label for="class-name">Class Name:</label>
                    <input type="text" id="class-name" name="className" required>

                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date" name="startDate" required>

                    <label for="end-date">End Date:</label>
                    <input type="date" id="end-date" name="endDate" required>

                    <label for="day-of-week">Day of the Week:</label>
                    <select id="day-of-week" name="dayOfWeek" required>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>

                    <label for="start-time">Start Time:</label>
                    <input type="time" id="start-time" name="startTime" required>

                    <label for="end-time">End Time:</label>
                    <input type="time" id="end-time" name="endTime" required>

                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" required>

                    <label for="max-participants">Max Participants:</label>
                    <input type="number" id="max-participants" name="maxParticipants" required>

                    <label for="price-member">Price for Members:</label>
                    <input type="number" step="0.01" id="price-member" name="priceMember" required>

                    <label for="price-nonmember">Price for Non-Members:</label>
                    <input type="number" step="0.01" id="price-nonmember" name="priceNonMember" required>

                    <label for="prerequisite-class">Prerequisite Class ID (optional):</label>
                    <input type="number" id="prerequisite-class" name="prerequisiteClassID">

                    <button type="submit">Create Class</button>
                </form>
            </div>
        </div>

        <!-- Right Column (Calendar, Event Form, Event List, Upcoming Classes) -->
        <div id="right-column">

            <!-- Class List (New Section for Displaying Current Classes) -->
            <div id="class-list">
                <h3>Upcoming Classes</h3>
                <ul id="classes">
                    <!-- Classes will be dynamically loaded here -->
                </ul>
            </div>
        </div>
    </div>

    <script src="calendar.js"></script>
    <script>
        // Fetch and display current classes
        function loadClasses() {
            fetch('php/get_classes.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const classList = document.getElementById('classes');
                        classList.innerHTML = ''; // Clear existing classes
                        data.classes.forEach(classItem => {
                            const li = document.createElement('li');
                            li.textContent = `${classItem.className} - ${classItem.startDate}`;
                            classList.appendChild(li);
                        });
                    } else {
                        console.error('Failed to load classes:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching classes:', error);
                });
        }

        // Load classes when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadClasses();
        });

        // Handle form submission for class creation
        document.getElementById('create-class-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const form = event.target;
            const formData = new FormData(form);

            fetch('php/create_class.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Class created successfully!');
                    form.reset(); // Clear the form
                    loadClasses(); // Refresh the list of classes
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error during submission: ' + error.message);
            });
        });
    </script>
</body>
<footer>
    <p>YMCA © 2024</p>
</footer>
</html>
