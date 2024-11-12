<!DOCTYPE html>
<html>
    <head>
        <title>YMCA | Manage Members</title>
        <link rel="stylesheet" type="text/css" href="css/ymca-main.css" />
    </head>

    <body>
        <?php include 'php/nav_mgr.php'; ?>

        <img src="img/Designer.png" alt="YMCA" width="200" height="200">
        <h1>Manage Members</h1>

        <!-- Search Form -->
        <div id="searchFormContainer" style="width: 90%; margin: 20px auto;">
            <h3>Search for Members</h3>
            <form id="searchForm">
                <label for="searchType">Search By:</label>
                <select id="searchType" name="searchType">
                    <option value="email">Email</option>
                    <option value="phone">Phone Number</option>
                    <option value="firstName">First Name</option>
                    <option value="lastName">Last Name</option>
                </select>

                <input type="text" id="searchText" name="searchText" placeholder="Enter search text">
                <button type="button" onclick="searchMembers()">Search</button>
                <button type="button" onclick="viewAllMembers()">View All Members</button>
            </form>
        </div>

        <!-- Results Table -->
        <div id="resultsContainer" style="width: 90%; margin: 20px auto;">
            <h3>Search Results</h3>
            <table id="resultsTable" border="1" style="width: 100%; text-align: center;">
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
                <tbody>
                    <!-- Table rows will be populated dynamically with JavaScript -->
                </tbody>
            </table>
        </div>

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

        <!-- Edit Form -->
        <div id="editFormContainer" style="width: 50%; margin: 20px auto; display: none;">
            <h3>Edit Member Information</h3>
            <form id="editForm">
                <input type="hidden" id="editMemberId" name="memberId">

                <label for="editFirstName">First Name:</label>
                <input type="text" id="editFirstName" name="firstName">

                <label for="editLastName">Last Name:</label>
                <input type="text" id="editLastName" name="lastName">

                <label for="editEmail">Email:</label>
                <input type="email" id="editEmail" name="email">

                <label for="editPhone">Phone Number:</label>
                <input type="text" id="editPhone" name="phone">

                <label for="editRole">Role:</label>
                <select id="editRole" name="role">
                    <option value="Member">Member</option>
                    <option value="NonMember">NonMember</option>
                    <option value="Staff">Staff</option>
                    <option value="Supervisor">Supervisor</option>
                    <option value="Admin">Admin</option>
                </select>

                <button type="button" onclick="saveMember()">Save Changes</button>
                <button type="button" onclick="cancelEdit()">Cancel</button>
            </form>
        </div>
    </body>

    <footer>
        <p>YMCA &copy; 2024</p>
    </footer>
    
    <script src="js/manageMembers.js"></script>
</html>
