<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YMCA | Admin Report</title>
    <link rel="stylesheet" href="css/ymca-main.css">
</head>
<body>
    <?php include 'php/nav_mgr.php'; ?>
    <img src="img/Designer.png" alt="YMCA" width="200" height="200">
    
    <!-- Header -->
    <header class="adminReportHeader">
        <h1>Admin Report - User Program Registration</h1>
    </header>

    <!-- Main Content -->
    <div class="adminReportContainer">

    <!-- Date Range Form -->
    <section class="dateRangeFormContainer">
        <h3>Select Date Range</h3>
        <form id="dateRangeForm" class="dateRangeForm">
            <div style="margin-bottom: 10px;">
                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" />
            </div>
            <div style="margin-bottom: 10px;">
                <label for="endDate">End Date:</label>
                <input type="date" id="endDate" />
            </div>
            <div style="margin-bottom: 10px; text-align: left;">
                <label for="includeAllClasses" style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="includeAllClasses" style="margin-right: 5px;" />
                    Include overlapping classes
                </label>
            </div>
            <div>
                <button type="button" onclick="getAdminReport()">Generate Report</button>
            </div>
        </form>
    </section>

        <!-- Report Table -->
        <section class="reportTableContainer">
            <h3>Report Results</h3>
            <table id="adminReportTable">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Class Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated dynamically -->
                </tbody>
            </table>
        </section>
        
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 YMCA. All Rights Reserved.</p>
    </footer>

    <script src="js/admin_report.js"></script>
</body>
</html>
