<!DOCTYPE html>
<html lang="en">
<head>
    <title>YMCA | Modify Class</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/ymca-main.css" />
</head>
<body>
    <?php include 'php/nav_mgr.php'; ?>
    <img src="img/Designer.png" alt="YMCA" width="200" height="200">
    <h1>Modify Class</h1>
    <div id="class-form">
        <form id="modify-class-form" action="php/modify_class.php" method="POST">

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
                <label><input type="checkbox" name="dayOfWeek" value="Monday"> Monday</label>
                <label><input type="checkbox" name="dayOfWeek" value="Tuesday"> Tuesday</label>
                <label><input type="checkbox" name="dayOfWeek" value="Wednesday"> Wednesday</label>
                <label><input type="checkbox" name="dayOfWeek" value="Thursday"> Thursday</label>
                <label><input type="checkbox" name="dayOfWeek" value="Friday"> Friday</label>
                <label><input type="checkbox" name="dayOfWeek" value="Saturday"> Saturday</label>
                <label><input type="checkbox" name="dayOfWeek" value="Sunday"> Sunday</label>
            </div>

            <label for="start-time">Start Time:</label>
            <input type="time" id="start-time" name="startTime" required>

            <label for="end-time">End Time:</label>
            <input type="time" id="end-time" name="endTime" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="max-participants">Max Participants:</label>
            <input type="number" id="max-participants" name="maxParticipants" required>

            <label for="current-participant-count">Current Participant Count:</label>
            <input type="number" id="current-participant-count" readonly name="currentParticipantCount" style="background-color: #f0f0f0; color: #888;" />

            <label for="price-staff">Price for Staff:</label>
            <input type="number" step="0.01" id="price-staff" name="priceStaff" required>

            <label for="price-member">Price for Members:</label>
            <input type="number" step="0.01" id="price-member" name="priceMember" required>

            <label for="price-nonmember">Price for Non-Members:</label>
            <input type="number" step="0.01" id="price-nonmember" name="priceNonMember" required>

            <label for="prerequisite-class">Prerequisite Class Name (optional):</label>
            <input type="text" id="prerequisite-class" name="prerequisiteClassName">

            <button type="submit">Update Class</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const urlParams = new URLSearchParams(window.location.search);
            const classID = urlParams.get('classID');
            if (classID) {
                document.getElementById('class-id').value = classID;
                await loadClassDetails(classID);
            }

            const form = document.getElementById('modify-class-form');

            form.addEventListener('submit', async function(event) {
                event.preventDefault(); // Prevent the default form submission

                const formData = new FormData(form);

                try {
                    const response = await fetch('php/modify_class.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (result.status === 'success') {
                        alert(result.message); // Show success message
                        window.location.href = 'manageClasses-page.php'; // Redirect after success
                    } else {
                        alert('Error: ' + result.message); // Show error message if any
                    }
                } catch (error) {
                    console.error('Error submitting form:', error);
                    alert('An error occurred while updating the class.');
                }
            });
        });

        // Fetch and populate form fields with class details
        async function loadClassDetails(classID) {
            try {
                const response = await fetch(`php/get_class_details.php?classID=${classID}`);
                const data = await response.json();

                if (data.status === 'success' && data.class) {
                    const cls = data.class;

                    document.getElementById('class-id').value = cls.ClassID ?? '';
                    document.getElementById('class-name').value = cls.ClassName ?? '';
                    document.getElementById('class-description').value = cls.ClassDescription ?? '';
                    
                    document.getElementById('start-date').value = cls.StartDate ? formatDate(cls.StartDate) : '';
                    document.getElementById('end-date').value = cls.EndDate ? formatDate(cls.EndDate) : '';

                    // Populate Day of the Week checkboxes
                    const days = cls.DayOfWeek ? cls.DayOfWeek.split(',') : [];
                    days.forEach(day => {
                        const checkbox = document.querySelector(`#day-of-week input[value="${day.trim()}"]`);
                        if (checkbox) checkbox.checked = true;
                    });

                    document.getElementById('start-time').value = cls.StartTime ?? '';
                    document.getElementById('end-time').value = cls.EndTime ?? '';
                    document.getElementById('location').value = cls.Location ?? '';
                    document.getElementById('max-participants').value = cls.MaxParticipants ?? '';
                    document.getElementById('current-participant-count').value = cls.CurrentParticipantCount ?? '';
                    document.getElementById('price-staff').value = cls.PriceStaff ?? '';
                    document.getElementById('price-member').value = cls.PriceMember ?? '';
                    document.getElementById('price-nonmember').value = cls.PriceNonMember ?? '';
                    document.getElementById('prerequisite-class').value = cls.PrerequisiteClassName ?? '';
                } else {
                    console.error('Failed to load class details:', data.message);
                }
            } catch (error) {
                console.error('Error fetching class details:', error);
            }
        }

        // Utility function to format dates as yyyy-MM-dd
        function formatDate(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
    </script>
</body>
<footer>
    <p>YMCA © 2024</p>
</footer>
</html>
