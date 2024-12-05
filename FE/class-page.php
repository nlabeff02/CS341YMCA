<!DOCTYPE html>
<html>
<head>
    <title>YMCA | Classes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/ymca-main.css" />
</head>
<body>
    <?php include 'php/nav_mgr.php'; ?>
    <img src="img/Designer.png" alt="YMCA" width="200" height="200">
    <h1>Upcoming Classes and Programs</h1>

    <!-- Search Section -->
    <div id="searchClassesContainer" style="width: 90%; margin: 20px auto;">
        <h3>Search for Classes</h3>
        <form id="searchClassesForm" style="margin-bottom: 20px;">
            <label for="classSearchType">Search By:</label>
            <select id="classSearchType">
                <option value="className">Class Name</option>
                <option value="startDate">Start Date</option>
                <option value="endDate">End Date</option>
                <option value="classLocation">Location</option>
            </select>
            <input type="text" id="classSearchText" placeholder="Enter search text" />
            <button type="button" onclick="searchClasses()">Search</button>
            <button type="button" onclick="getFutureClassesPublic()">View All</button>
        </form>
    </div>

    <!-- Table to display upcoming classes -->
    <div id="classTableContainer" style="width: 90%; margin: 20px auto;">
        <h3>Available Classes</h3>
        <table id="classesTable" border="1" style="width: 100%; text-align: center;">
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Class Name</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Class Location</th>
                    <th>Max Participants</th>
                    <th>Currently Enrolled</th>
                    <th>Member Cost</th>
                    <th>Non-Member Cost</th>
                    <th>Prerequisite</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table rows will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
</body>
<footer>
    <p>YMCA © 2024</p>
</footer>
<script>
    // Set isLoggedIn based on session status
    const isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
    console.log("User is logged in:", isLoggedIn); // Debugging line
</script>
<script src="js/classes.js"></script>
<script>
    // Load future member classes when the page loads
    document.addEventListener('DOMContentLoaded', () => {
        getFutureClassesPublic();
    });
</script>
</html>
