<?php
session_start();
?>
<nav>
    <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="programs_staff.html">Events</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="logout.php">Logout</a></li>
            <li><span><?php echo $_SESSION['user']['firstName'] . " (" . $_SESSION['user']['role'] . ")"; ?></span></li>
        <?php else: ?>
            <li><a href="login.html">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
