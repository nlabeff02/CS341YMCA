
<!DOCTYPE html>
<html lang="en">
<head>
    <title>YMCA | Manage Classes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/ymca-main.css" />
</head>
<body>
    <?php include 'php/nav_mgr.php'; ?>
    <img src="img/Designer.png" alt="YMCA" width="200" height="200">
    <h1>Manage Classes</h1>
    <!-- Table to display classes -->
    <div id="classTableContainer">
        <table id="classesTable" border="1">
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Class Name</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Day of Week</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Location</th>
                    <th>Max Participants</th>
                    <th>Current Enrollment</th>
                    <th>Price for Staff</th>
                    <th>Price for Members</th>
                    <th>Price for Non-Members</th>
                    <th>Prerequisite</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Class rows will be dynamically populated here -->
            </tbody>
        </table>
    </div>

    <!-- Include classes.js script -->
    <script src="js/classes.js"></script>
    <script>
        // Load all classes when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            getAllClasses();
        });
    </script>
</body>
<footer>
    <p>YMCA © 2024</p>
</footer>
</html>
