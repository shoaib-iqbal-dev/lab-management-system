<?php
include "db.php";
include "header.php";
include "auth_check.php";

date_default_timezone_set("Asia/Karachi");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id']; // Must be set during login

// Get today's date
$today = date('Y-m-d');

// Query to calculate today's earnings for the logged-in user
$earningQuery = "
    SELECT SUM(r.total_fee) AS total 
FROM receipts r
WHERE DATE(r.payment_date) = '$today' AND r.billed_by = '$user_id'
";

$earningResult = mysqli_query($conn, $earningQuery);

$today_earning = 0;
if ($earningResult && mysqli_num_rows($earningResult) > 0) {
    $row = mysqli_fetch_assoc($earningResult);
    $today_earning = $row['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Lab Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="dashboard">

    <div class="card" onclick="location.href='add_patient.php';">
        <h2>Add New Patient</h2>
        <p>Enter new patient's information.</p>
    </div>

    <div class="card" onclick="location.href='search_patient.php';">
        <h2>Search Patient / Patient List</h2>
        <p>Find or view patient records.</p>
    </div>

    <div class="card" onclick="location.href='my_earning_details.php';" style="cursor: pointer;">
    <h2>Today's Earnings</h2>
    <p style="font-size: 24px; font-weight: bold;">Rs <?php echo number_format($today_earning); ?></p>
    <p style="font-size: 14px;">Click to view detailed receipts</p>
</div>


    <?php if ($role == 'admin') { ?>
        <div class="card" onclick="location.href='all_earnings.php';">
            <h2>Total Earnings of All Users</h2>
            <p>Click to see the detailed earnings of all users for today.</p>
        </div>

        <div class="card" onclick="location.href='manage_tests.php';">
            <h2>Manage Tests & Fees</h2>
            <p>Update available tests and fees.</p>
        </div>

        <div class="card" onclick="location.href='manage_users.php';">
            <h2>Manage Users</h2>
            <p>Add or edit system users.</p>
        </div>

        <div class="card" onclick="location.href='sales_report.php';">
            <h2>Sales Reports</h2>
            <p>View daily, monthly, yearly reports.</p>
        </div>

        <div class="card" onclick="location.href='daily_summary.php';">
            <h2>Daily Summary</h2>
            <p>View today's patients, bills, and tests.</p>
        </div>

        <div class="card" onclick="location.href='doctor_commissions_list.php';">
            <h2>Commission Management</h2>
            <p>Manage Commission percentages.</p>
        </div>
		
		<div class="card" onclick="location.href='return_history.php';">
			<h2>Returns</h2>
			<p>Manage test returns and issue refunds.</p>
		</div>

    <?php } ?>

</main>

<?php include "footer.php"; ?>
</body>
</html>
