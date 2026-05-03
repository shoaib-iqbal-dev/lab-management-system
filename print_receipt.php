<?php
session_start();
include "db.php";
include "auth_check.php";

$username = $_SESSION['username'] ?? '';

// Check if receipt_id is provided
if (!isset($_GET['receipt_id'])) {
    echo "No receipt found.";
    exit();
}

$receipt_id = (int)$_GET['receipt_id'];

// Fetch the receipt
$receipt_result = mysqli_query($conn, "SELECT * FROM receipts WHERE receipt_id = $receipt_id LIMIT 1");
if (!$receipt_result || mysqli_num_rows($receipt_result) == 0) {
    echo "Receipt not found.";
    exit();
}
$receipt = mysqli_fetch_assoc($receipt_result);

// Fetch the father_name from patients table using patient_id
$father_name = 'N/A';
if (isset($receipt['patient_id'])) {
    $patient_id = (int)$receipt['patient_id'];
    $patient_result = mysqli_query($conn, "SELECT father_name FROM patients WHERE id = $patient_id LIMIT 1");
    if ($patient_result && mysqli_num_rows($patient_result) > 0) {
        $patient_data = mysqli_fetch_assoc($patient_result);
        $father_name = $patient_data['father_name'];
    }
}

// Get billed_by user's username
$billed_by_id = $receipt['billed_by'];
$user_result = mysqli_query($conn, "SELECT username FROM users WHERE id = $billed_by_id LIMIT 1");
$billed_by_username = 'Unknown';

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user = mysqli_fetch_assoc($user_result);
    $billed_by_username = ucfirst($user['username']);
}

// Fetch tests
$tests_result = mysqli_query($conn, "SELECT * FROM receipt_tests WHERE receipt_id = $receipt_id");
if (!$tests_result) {
    echo "Error fetching tests: " . mysqli_error($conn);
    exit();
}

// Calculate total
$total = 0;
$tests = [];
while ($test = mysqli_fetch_assoc($tests_result)) {
    $total += $test['test_fee'];
    $tests[] = $test;
}

// Calculate total test_discount for this receipt
$discount_result = mysqli_query($conn, "SELECT SUM(test_discount) AS total_discount FROM receipt_tests WHERE receipt_id = $receipt_id");
$discount_row = mysqli_fetch_assoc($discount_result);
$discount = (float)$discount_row['total_discount'];
$net_total = $total - $discount;

?>


<!DOCTYPE html>
<html>
<head>
    <title>Print Bill</title>
    <link rel="stylesheet" href="styles.css"> <!-- External Stylesheet (Optional) -->
</head>
<header>
	<img src="loggo.png" alt="lab logo.png" style="width: 120px;margin-bottom: 0px;padding-bottom: 0px;">
   
   <h1>
        <a href="<?php echo isset($_SESSION['username']) ? 'dashboard.php' : 'login.php'; ?>" class="header-link">
            Lab Management System
        </a>
    </h1>
	
    <?php if (!empty($username)) { ?>
        <h2 style="text-align: center; color:white;">Welcome, <?php echo ucfirst($username); ?>!</h2>
        <a href="logout.php" class="logout-btn" style="text-decoration: none; color: inherit;">Logout</a>
    <?php } ?>
</header>
<body>
<div class="patient-info">

<div style="text-align: center; margin-top: 0px;">
    <a href="dashboard.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back to Dashboard</a>
</div>

    <div class="receipt-container">
        <div class="print-button">
            <button onclick="printReceipt()">🖨️ Print Receipt</button>
        </div>

        <br>

        <div class="receipt-box" id="receipt"style="padding-right: 0px; padding-left: 0px;">
            <div style="text-align: center;">
                <img src="logggo.png" alt="Lab Logo" style="width: 100px; height: auto; margin-bottom: 0px;">
            </div>

          

            <table class="table" style="width: 100%; margin: 20px auto; border-collapse: collapse;margin-bottom: 0px;margin-top: 0px;text-align: left; border:hidden;">
    <tr>
        <th style="border:hidden;">Patient Name</th>
        <td style="border:hidden;"><?= htmlspecialchars($receipt['patient_name'] ?? 'N/A') ?></td>
        <th style="border:hidden;">MR No</th>
            <td><?= htmlspecialchars($receipt['mr_no'] ?? 'N/A') ?></td>
    </tr>
    <tr>
        <th style="border:hidden;">Age</th>
        <td style="border:hidden;"><?= htmlspecialchars($receipt['age'] ?? 'N/A') ?></td>
        <th style="border:hidden;">Gender</th>
        <td style="border:hidden;"><?= htmlspecialchars($receipt['gender'] ?? 'N/A') ?></td>
    </tr>
    <tr>
        <th style="border:hidden;">Date & Time</th>
        <td style="border:hidden;">
                        <?php 
                        if (isset($receipt['payment_date']) && strtotime($receipt['payment_date']) > 0) {
                            echo date('d-m-Y H:i:s', strtotime($receipt['payment_date']));
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
        <th style="border:hidden;">Billed By</th>
		<td style="border:hidden;"><?= htmlspecialchars($billed_by_username) ?></td>
    </tr>

</table>
            
            <table class="table" style="width: 100%; margin: 20px auto; border-collapse: collapse;margin-top: 0px;margin-bottom: 0px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Test Name</th>
                        <th>Fee (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tests as $index => $test): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($test['test_name']) ?></td>
                        <td ><?= number_format($test['test_fee'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total-row" style="text-align:right;">
                        <td colspan="2" style="text-align:right;"><strong>Actual Total</strong></td>
                        <td><strong>Rs. <?= number_format($total, 2) ?></strong></td>
                    </tr>
                    <tr class="total-row" style="text-align:right;">
                        <td colspan="2" style="text-align:right;"><strong>Discount</strong></td>
                        <td><strong>Rs. <?= number_format($discount, 2) ?></strong></td>
                    </tr>
                    <tr class="total-row" style="text-align:right;">
                        <td colspan="2" style="text-align:right;"><strong>Total After Discount</strong></td>
                        <td><strong>Rs. <?= number_format($net_total, 2) ?></strong></td>
                    </tr>
                </tbody>
            </table>

            <p class="center-text" style="margin-top: 10px;margin-bottom: 0px;">🧾 Thank you for your payment!</p>
            <p class="center-text" style="margin-top: 0px;margin-bottom: 0px;">2nd Floor, Qazi Plaza | CMH Road, Muzaffarabad AJK</p>
            <p class="center-text" style="margin-top: 0px;margin-bottom: 0px;">Contact: 03470540453</p>
        </div>
    </div>
</div>

<footer>
    <p>Developed by Solutions Xpert &copy; <?= date('Y'); ?></p>
</footer>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
}

header {
    background-color: #3498db;
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.receipt-container {
    font-size: 10px;
    margin: 3px;
    font-family: 'Tahoma';
}
.receipt-box {
    background: #fff;
    border: 1px solid #ccc;
}
.center-text {
    text-align: center;
}
table, td, th {
            border: 1px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
}
.test-table th {
    background-color: #f0f0f0;
}
.total-row {
    background-color: #e0ffe0;
}
.print-button {
    text-align: center;
    margin-top: 20px;
}
.navigation-buttons {
    margin-top: 20px;
    text-align: center;
}
.navigation-buttons button {
    margin: 5px;
    padding: 10px 15px;
    font-size: 16px;
    cursor: pointer;
}


@media print {
  @page {
    size: 80mm auto;
    margin: 4mm;
  }

  * {
    box-sizing: border-box;
  }

  body {
    width: 72mm;
    margin: 0 auto;
    font-size: 10px;
  }

  body * {
    visibility: hidden;
  }

  #receipt, #receipt * {
    visibility: visible;
  }

  #receipt {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }

  .print-button,
  .navigation-buttons,
  header,
  footer {
    display: none !important;
  }

  .receipt-container {
    margin: 0;
    padding: 0;
    width: 100%;
  }

  .receipt-box {
    border: none;
    box-shadow: none;
    padding: 0;
  }

  .info-table, .test-table {
    width: 100%;
  }
}





.header-link {
    text-decoration: none;
    color: white;
}
.logout-btn {
    color: white;
    background: #e74c3c;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 5px;
}
.patient-info {
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

<script>
function printReceipt() {
    window.print();
}
</script>

</body>
</html>
<?php include "footer.php"; ?>