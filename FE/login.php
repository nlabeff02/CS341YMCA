<!DOCTYPE html>
<html>
<head>
    <title>YMCA | Login</title>
    <link rel="stylesheet" type="text/css" href="css/ymca-main.css" />
</head>
<body>
    <?php include 'php/header.php'; ?>
    <img src="img/Designer.png" alt="YMCA" width="200" height="200">
    <h1>Login</h1>
    <div class="form-container">
        <form id="login-form" class="form" action="php/login.php" method="POST">
            <h2>Login</h2>
            <input type="text" id="username" placeholder="Email" required>
            <input type="password" id="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <h3>No Account? <a href="registration.php">Click Here</a></h3>
        </form>
        <div id="error-message" style="color: red; display: none;"></div>
    </div>
    <div id="loggedIn" style="display: none;">
        <h1>Welcome back!</h1>
        <button onclick="logout()">Log Out</button>
    </div>
</body>
<footer>
    <p>YMCA © 2024</p>
</footer>
<script src="js/login.js"></script>
</html>
