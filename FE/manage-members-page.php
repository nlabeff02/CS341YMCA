<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>YMCA | Manage Members</title>
        <link rel="stylesheet" href="css/ymca-main.css">
    </head>
    <body>
        <?php include 'php/nav_mgr.php'; ?>

        <header class="ymca-page-header">
            <img class="ymca-logo" src="img/Designer.png" alt="YMCA Logo">
            <h1 class="ymca-page-title">Manage Members</h1>
        </header>

        <div class="ymca-container">
            <!-- Search Form -->
            <section class="ymca-search-form-container">
                <h3 class="ymca-section-title">Search for Members</h3>
                <form id="searchForm" class="ymca-search-form">
                    <div class="ymca-form-group">
                        <label for="searchType" class="ymca-form-label">Search By:</label>
                        <select id="searchType" name="searchType" class="ymca-form-input">
                            <option value="email">Email</option>
                            <option value="phone">Phone Number</option>
                            <option value="firstName">First Name</option>
                            <option value="lastName">Last Name</option>
                        </select>
                    </div>
                    <div class="ymca-form-group">
                        <label for="searchText" class="ymca-form-label">Search Text:</label>
                        <input type="text" id="searchText" name="searchText" class="ymca-form-input" placeholder="Enter search text">
                    </div>
                    <div class="ymca-form-actions">
                        <button type="button" class="ymca-btn ymca-primary-btn" onclick="searchMembers()">Search</button>
                        <button type="button" class="ymca-btn ymca-secondary-btn" onclick="viewAllMembers()">View All Members</button>
                    </div>
                </form>
            </section>

            <!-- Results Table -->
            <section class="ymca-members-results-container">
                <h3 class="ymca-section-title">Search Results</h3>
                <table id="resultsTable" class="ymca-members-results-table">
                    <thead>
                        <tr>
                            <th>Member ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="resultsTableBody">
                        <!-- Table rows populated dynamically -->
                    </tbody>
                </table>
            </section>

            <!-- Registrations Modal -->
            <section id="registrationsContainer" class="registrations-container" style="display: none;">
                <h3>Member Registrations</h3>
                <div class="registrations-table-container">
                    <table id="registrationsTable" class="registrations-table">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody id="registrationsTableBody">
                            <!-- Data populated dynamically -->
                        </tbody>
                    </table>
                </div>
                <!-- Close Button -->
                <div class="close-button-container">
                    <button onclick="closeRegistrationsModal()">Close</button>
                </div>
            </section>

            <!-- Edit Form Modal -->
            <section id="editFormContainer" class="ymca-modal-container" style="display: none;">
                <h3 class="ymca-modal-title">Edit Member Information</h3>
                <form id="editForm" class="ymca-modal-form">
                    <input type="hidden" id="editMemberId" name="memberId">
                    <div class="ymca-form-group">
                        <label for="editFirstName" class="ymca-form-label">First Name:</label>
                        <input type="text" id="editFirstName" name="firstName" class="ymca-form-input">
                    </div>
                    <div class="ymca-form-group">
                        <label for="editLastName" class="ymca-form-label">Last Name:</label>
                        <input type="text" id="editLastName" name="lastName" class="ymca-form-input">
                    </div>
                    <div class="ymca-form-group">
                        <label for="editEmail" class="ymca-form-label">Email:</label>
                        <input type="email" id="editEmail" name="email" class="ymca-form-input">
                    </div>
                    <div class="ymca-form-group">
                        <label for="editPhone" class="ymca-form-label">Phone Number:</label>
                        <input type="text" id="editPhone" name="phone" class="ymca-form-input">
                    </div>
                    <div class="ymca-form-group">
                        <label for="editRole" class="ymca-form-label">Role:</label>
                        <select id="editRole" name="role" class="ymca-form-input">
                            <option value="Member">Member</option>
                            <option value="NonMember">NonMember</option>
                            <option value="Staff">Staff</option>
                            <option value="Supervisor">Supervisor</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="ymca-form-actions">
                        <button type="button" class="ymca-btn ymca-primary-btn" onclick="saveMember()">Save Changes</button>
                        <button type="button" class="ymca-btn ymca-secondary-btn" onclick="cancelEdit()">Cancel</button>
                    </div>
                </form>
            </section>
        </div>

        <footer>
            <p>&copy; 2024 YMCA. All Rights Reserved.</p>
        </footer>

        <script src="js/manageMembers.js"></script>
    </body>
</html>