<?php
session_start();
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

// Get start and end dates
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

$user_id = $_SESSION['user_id']; // assuming stored during login

// Use `patients` table for counting patients
if ($role == 'admin') {
    // Count total earnings, bills, and tests from receipts
    $earningQuery = "SELECT SUM(total_fee) as total FROM receipts WHERE DATE(payment_date) BETWEEN '$start_date' AND '$end_date'";
    $billsQuery = "SELECT COUNT(*) as total FROM receipts WHERE DATE(payment_date) BETWEEN '$start_date' AND '$end_date'";

    // New query to count the total patients registered in the date range
    $patientsQuery = "SELECT COUNT(DISTINCT mr_no) as total FROM patients WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date'";

    // New query to get the total number of tests
    $testsQuery = "SELECT COUNT(*) as total_tests FROM receipt_tests 
                   JOIN receipts ON receipts.receipt_id = receipt_tests.receipt_id
                   WHERE DATE(receipts.payment_date) BETWEEN '$start_date' AND '$end_date'";
} else {
    // Count total earnings, bills, and tests for a specific user
    $earningQuery = "SELECT SUM(total_fee) as total FROM receipts WHERE DATE(payment_date) BETWEEN '$start_date' AND '$end_date' AND billed_by = '$user_id'";
    $billsQuery = "SELECT COUNT(*) as total FROM receipts WHERE DATE(payment_date) BETWEEN '$start_date' AND '$end_date' AND billed_by = '$user_id'";

    // New query to count the total patients registered by the user in the date range
    $patientsQuery = "SELECT COUNT(DISTINCT mr_no) as total FROM patients WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date' AND billed_by = '$user_id'";

    // New query to get the total number of tests for the logged-in user
    $testsQuery = "SELECT COUNT(*) as total_tests FROM receipt_tests 
                   JOIN receipts ON receipts.receipt_id = receipt_tests.receipt_id
                   WHERE DATE(receipts.payment_date) BETWEEN '$start_date' AND '$end_date'
                   AND receipts.billed_by = '$user_id'";
}

// Execute queries
$earningResult = mysqli_query($conn, $earningQuery);
$total_earning = ($earningResult) ? mysqli_fetch_assoc($earningResult)['total'] ?? 0 : 0;

$patientsResult = mysqli_query($conn, $patientsQuery);
$patients = ($patientsResult) ? mysqli_fetch_assoc($patientsResult)['total'] : 0;

$billsResult = mysqli_query($conn, $billsQuery);
$bills = ($billsResult) ? mysqli_fetch_assoc($billsResult)['total'] : 0;

// Execute the query for total tests
$testsResult = mysqli_query($conn, $testsQuery);
$tests = ($testsResult) ? mysqli_fetch_assoc($testsResult)['total_tests'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Summary - Lab Management System</title>
    <link rel="stylesheet" href="style.css">
	<style>
	.container1 {
            background: #f9f9f9;
            padding: 30px;
            margin: 20px 50px;
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
        }
	
			
						.back-button {
			display: inline-block;
			background-color: #007BFF;  /* Bootstrap-like blue */
			color: white;
			padding: 10px 20px;
			border-radius: 5px;
			text-decoration: none;
			font-weight: bold;
			font-size: 16px;
			transition: background-color 0.3s ease;
			margin-bottom: 20px;
			cursor: pointer;
		}

		.back-button:hover {
			background-color: #0056b3;  /* Darker blue on hover */
			text-decoration: none;
			color: white;
		}

		
	
	</style>
	
</head>
<body>
<main class="dailysummary">
 <br><br>
<div class="container1" style="margin-top: 0px;">
<div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php" class="back-button" style="margin-bottom: 20px;margin-top: -20px;">Back to Dashboard</a>
</div>
        <!-- Date Selection Form -->
<form method="GET" style="text-align: center; margin-bottom: 20px;">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>" required>

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>" required>

    <button type="submit">Show Summary</button>
</form>

    <br><br>
    <h2 style="text-align: center;margin-top: 10px;margin-bottom: 10px;">
        Summary from <?php echo $start_date; ?> to <?php echo $end_date; ?>
    </h2>

    <div class="dashboard">
        <div class="card"  onclick="location.href='admin_receipts_today.php';">
            <h3>Total Earnings</h3>
            <p>Rs <?php echo number_format($total_earning); ?></p>
        </div>

        <div class="card">
            <h3>Total Patients</h3>
            <p><?php echo $patients; ?></p>
        </div>

        <div class="card">
            <h3>Total Bills</h3>
            <p><?php echo $bills; ?></p>
        </div>

        <div class="card">
            <h3>Total Tests</h3>
            <p><?php echo $tests; ?></p>
        </div>
    </div>
	</div>
</main>
</body>
</html>

<?php include "footer.php"; ?>
