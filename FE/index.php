<!DOCTYPE html>
<html>
    <head>
        <title>YMCA | Home</title>
        <link rel="stylesheet" type="text/css" href="css/ymca-main.css" />
    </head>

    <body>
        <?php include 'php/nav_mgr.php'; ?>
        <div class="intro">
            <img src="img/Designer.png" alt="YMCA" width="200" height="200" />
            <div>
                <h1>Welcome to the YMCA</h1>
                <p>Our mission is to put health principles into practice through programs that build healthy spirit, mind, and body for all.</p>
            
            <h2>Who are we?</h2>
            <p>
                Welcome to the YMCA! We're thrilled to have you join our community, where we focus on building a healthier spirit, mind, and body for all. Whether you're here for fitness, family activities, or personal development, you'll find
                programs and support designed to help you thrive. Our diverse offerings include everything from group exercise classes and sports to childcare and community outreach, ensuring there's something for everyone. At the YMCA, we're
                more than just a gym, we're a place where friendships are made, goals are achieved, and families grow stronger together. Our dedicated team is here to guide and inspire you every step of the way. We can't wait to see what you'll
                accomplish with us! Welcome to the Y family!
            </p>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <img src="img/sportsPlay.jpg" alt="Avatar" style="width: 80%;" />
                <div class="container">
                    <h4><b>Membership</b></h4>
                    <p>Join today to become a part of our wonderful community!</p>
                    <button class="btn" onclick="window.location.href='login-page.php'">JOIN</button>
                </div>
            </div>
            <div class="card">
                <img src="img/swim.jpg" alt="Avatar" style="width: 80%;" />
                <div class="container">
                    <h4><b>Events</b></h4>
                    <p>Take a look at our upcoming classes!</p>
                    <button class="btn" onclick="window.location.href='class-page.php'">Classes</button>
                </div>
            </div>
        </div>
    </body>

    <footer>
        <p>YMCA &copy; 2024</p>
    </footer>
</html>
