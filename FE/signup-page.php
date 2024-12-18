<!DOCTYPE html>
<html>
<head>
    <title>YMCA | Register</title>
    <link rel="stylesheet" type="text/css" href="css/ymca-main.css" />
</head>
<body>
    <?php include 'php/nav_mgr.php'; ?>
    <header>
        <img src="img/Designer.png" alt="YMCA" width="200" height="200">
        <h1>Registration</h1>
    </header>

    <main>
        <div class="form-container">
            <form id="signup-form" class="form" action="php/register.php" method="POST">
                <h2>Sign Up</h2>
                <input type="text" id="new-firstname" placeholder="First Name" required>
                <input type="text" id="new-lastname" placeholder="Last Name" required>
                <input type="email" id="email" placeholder="Email" required>
                <input type="tel" id="phone-number" placeholder="Phone Number" required>
                <input type="password" id="new-password" placeholder="Password" required>
                <input type="password" id="confirm-password" placeholder="Confirm Password" required>
                <!-- <label>
                    <input type="checkbox" id="has-child"> Do you have a child to register?
                </label>
                <div id="children-info" style="display: none;">
                    <h3>Children Information</h3>
                    <div id="children-container">
                        <div class="child">
                            <input type="text" id="child-name" placeholder="Child's Name">
                            <input type="number" id="child-age" placeholder="Child's Age">
                        </div>
                    </div>
                    <button type="button" id="add-child">Add Another Child</button>
                </div> -->
                <button type="submit">Sign Up</button>
            </form>
        </div>
    </main>
    <footer>
        <p>YMCA © 2024</p>
    </footer>
    <script src="js/signup.js"></script>
</body>
</html>
