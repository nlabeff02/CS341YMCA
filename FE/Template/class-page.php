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
    <h1>Classes and Programs</h1>
    <div id="calendar">
        <div id="header">
            <button id="prev">Prev</button>
            <h2 id="month-year"></h2>
            <button id="next">Next</button>
        </div>
        <div id="days">
            <!-- Days will be dynamically generated here -->
        </div>
    </div>

    <h3 id="selected-date">Select a date</h3>
    <ul id="events">
        <!-- Events will be displayed here -->
    </ul>

    <div id="registration">
        <h3>Register for a Class</h3>
        <button onclick="register()">Register</button>
    </div>

    <footer>
        <p>YMCA © 2024</p>
    </footer>
    <script src="calendar.js"></script>
    <script>
        function register() {
            alert('Registration functionality coming soon!');
        }
    </script>
</body>
</html>
