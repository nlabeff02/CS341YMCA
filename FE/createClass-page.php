<!DOCTYPE html>
<html lang="en">
<head>
    <title>YMCA | Create New Class</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/ymca-main.css" />
</head>
<body>
    <?php include 'php/nav_mgr.php'; ?>
    <img src="img/Designer.png" alt="YMCA" width="200" height="200">
    <h1>Create New Class</h1>

    <div id="page-container">
        <!-- Left Column (New Class Form) -->
        <div id="left-column">
            <div id="class-form">
                <h2>Create New Class</h2>
                <form id="create-class-form">

                    <label for="class-id">Class ID:</label>
                    <input type="text" id="class-id" name="classID" readonly style="background-color: #f0f0f0; color: #888;" />

                    <label for="class-name">Class Name:</label>
                    <input type="text" id="class-name" name="className" required>

                    <label for="class-description">Class Description:</label>
                    <input type="text" id="class-description" name="classDescription" required>

                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date" name="startDate" required>

                    <label for="end-date">End Date:</label>
                    <input type="date" id="end-date" name="endDate" required>

                    <label>Day of the Week:</label>
                    <div id="day-of-week">
                        <label><input type="checkbox" name="dayOfWeek" value="Sunday"> Sunday</label>
                        <label><input type="checkbox" name="dayOfWeek" value="Monday"> Monday</label>
                        <label><input type="checkbox" name="dayOfWeek" value="Tuesday"> Tuesday</label>
                        <label><input type="checkbox" name="dayOfWeek" value="Wednesday"> Wednesday</label>
                        <label><input type="checkbox" name="dayOfWeek" value="Thursday"> Thursday</label>
                        <label><input type="checkbox" name="dayOfWeek" value="Friday"> Friday</label>
                        <label><input type="checkbox" name="dayOfWeek" value="Saturday"> Saturday</label>
                    </div>

                    <label for="start-time">Start Time:</label>
                    <input type="time" id="start-time" name="startTime" required>

                    <label for="end-time">End Time:</label>
                    <input type="time" id="end-time" name="endTime" required>

                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" required>

                    <label for="max-participants">Max Participants:</label>
                    <input type="number" id="max-participants" name="maxParticipants" required>

                    <label for="price-staff">Price for Staff:</label>
                    <input type="number" step="1" id="price-staff" name="priceStaff" required>

                    <label for="price-member">Price for Members:</label>
                    <input type="number" step="1" id="price-member" name="priceMember" required>

                    <label for="price-nonmember">Price for Non-Members:</label>
                    <input type="number" step="1" id="price-nonmember" name="priceNonMember" required>

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
                <h3>Classes</h3>
                <div id="class-search">
                    <label for="search-classes">Search Classes:</label>
                    <input type="text" id="search-classes" placeholder="Enter class name" onkeyup="filterClasses()">
                </div>
                <ul id="classes">
                    <!-- Classes will be dynamically loaded here -->
                </ul>
            </div>
        </div>
    </div>

    <script>
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
    <style>
        /* Add custom styles here */
        #day-of-week {
            
        }
    </style>
</body>
<footer>
    <p>YMCA © 2024</p>
</footer>
</html>