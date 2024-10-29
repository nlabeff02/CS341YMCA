<?php
session_start();
?>
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="programs.php">Events</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li><span><?php echo $_SESSION['user']['firstName'] . " (" . $_SESSION['user']['role'] . ")"; ?></span></li>
            <li><a href="php/logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login or Join</a></li>
        <?php endif; ?>
    </ul>
</nav>
