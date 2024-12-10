<?php
    session_start();
?>
<!-- Main Navigation Bar -->
<nav class="main-nav">
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="class-page.php">Classes</a></li>
        <li><a href="contact-page.php">Contact</a></li>
        <li><a href="about-page.php">About</a></li>

        <?php if (isset($_SESSION['user'])): ?>
            <li><span><?php echo $_SESSION['user']['firstName'] . " (" . $_SESSION['user']['role'] . ")"; ?></span></li>
            <li><a href="php/logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login-page.php">Login or Join</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Role-Based Navigation Bar (only visible when logged in) -->
<?php if (isset($_SESSION['user'])): ?>
    <nav class="role-nav">
        <ul>
            <?php if ($_SESSION['user']['role'] === 'Staff'): ?>
                <!-- Links for Staff -->
                <li><a href="manage-members-page.php">Manage Members</a></li>
                <li><a href="createClass-page.php">Create New Class</a></li>
                <li><a href="manageClasses-page.php">Manage Classes</a></li>
                <li><a href="members.php">My Classes</a></li>
            <?php elseif ($_SESSION['user']['role'] === 'Supervisor'): ?>
                <!-- Links for Supervisors -->
                <li><a href="manage-members-page.php">Manage Members</a></li>
                <li><a href="createClass-page.php">Create New Class</a></li>
                <li><a href="manageClasses-page.php">Manage Classes</a></li>
                <li><a href="members.php">My Classes</a></li>
            <?php elseif ($_SESSION['user']['role'] === 'Admin'): ?>
                <li><a href="members.php">My Classes</a></li>
                <li><a href="manageClasses-page.php">Manage Classes</a></li>
                <li><a href="manage-members-page.php">Manage Members</a></li>
                <li><a href="createClass-page.php">Create New Class</a></li>
                <li><a href="adminReport-page.php">Admin Report</a></li>
            <?php elseif ($_SESSION['user']['role'] === 'Member' || $_SESSION['user']['role'] === 'NonMember'): ?>
                <!-- Links for Members/Nonmembers -->
                <li><a href="members.php">My Classes</a></li>
                <li><a href="class-page.php">Search Classes</a></li>
            <?php endif; ?>
        </ul>
        <?php
            $personID = $_SESSION['user']['personID'];
            $personRole = $_SESSION['user']['role'];
            echo "<script>const personID = " . json_encode($personID) . ";
                    const personRole = " . json_encode($personRole) . ";
                    console.log(personID, personRole);</script>";
        ?>
    </nav>
<?php endif; ?>

<!-- Styles for Role-Based Navigation Bar -->
<style>
    .role-nav {
        color: white;
        background-color: #219ebc; /* Distinct color for role-based nav */
        padding: 10px;
        text-align: center;
        width: 100%;
    }

    .role-nav ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: center;
    }

    .role-nav li {
        margin: 0 15px;
    }

    .role-nav a {
        color: white;
        text-decoration: none;
        font-size: 16px;
    }

    .role-nav a:hover {
        text-decoration: underline;
    }
</style>
