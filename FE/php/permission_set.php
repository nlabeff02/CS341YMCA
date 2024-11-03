<?php
// Add extra links if the user is a staff member
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Staff') {
    $extraNavLinks = [
        'Manage Members' => 'manage_members.php',
        'Search Members' => 'search_members.php',
        'Create Class' => 'create_class.php',
        'Manage Classes' => 'manage_classes.php',
        'Edit Classes' => 'edit_classes.php',
    ];
}

// Expandable logic for other roles can be added here in the future

// Function to display extra links if available
function displayExtraNavLinks($links) {
    if (!empty($links)) {
        echo '<ul class="extra-nav-links">';
        foreach ($links as $linkText => $linkUrl) {
            echo "<li><a href='$linkUrl'>$linkText</a></li>";
        }
        echo '</ul>';
    }
}

