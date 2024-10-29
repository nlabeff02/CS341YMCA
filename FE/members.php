<!DOCTYPE html>
<html>
<head>
    <title>YMCA | Classes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/ymca-main.css" />
</head>
<body>
    <?php include 'php/header.php'; ?>
    <img src="img/Designer.png" alt="YMCA" width="200" height="200">

    <div id="content">
        <?php if (isset($_SESSION['user'])): ?>
            <!-- Content for logged-in users -->
            <h3>Registered Classes</h3>
            <table id="classesTable" style="border: solid; width: 80%; margin: 0 auto; text-align: center; color: white;">
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Day(s)</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data here -->
                </tbody>
            </table>
        <?php else: ?>
            <!-- Message for users not logged in -->
            <p style="text-align: center; color: #FF0000; margin-top: 20px;">
                <a href="login.php" style="color: #8ecae6; text-decoration: underline;">Login</a>
                to view your classes or
                <a href="registration.php" style="color: #8ecae6; text-decoration: underline;">Join the Y</a>
                 to start having fun!
            </p>
        <?php endif; ?>
    </div>

    <footer>
        <p>YMCA © 2024</p>
    </footer>

</body>
</html>
