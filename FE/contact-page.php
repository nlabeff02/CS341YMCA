<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>YMCA | Contact</title>
        <link rel="stylesheet" type="text/css" href="css/ymca-main.css"/>
    </head>
    <body>
        <?php include 'php/nav_mgr.php'; ?>
        <img src="img/Designer.png" alt="YMCA" width=200 height = 200>
        <h1>Contact Us</h1>
        <h2>Address</h2>
        <p>1234 YMCA Drive</p>
        <p>La Crosse, WI 54601</p>
        <h2>Phone</h2>
        <p>Phone: 608-456-7890</p>
        <h2>Email</h2>
        <p>Email: smalltown@YMCA.com </p>
        <h2> Or Reach Out Below: </h2>
        <div class="container">
            <form action="#" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" placeholder="Your Message" required></textarea>
                <button type="submit">Send</button>
            </form>
        </div>
    </body>
    <footer>
        <p>YMCA &copy; 2024</p>
    </footer>
    <script src="js/contact.js"></script>
</html>
