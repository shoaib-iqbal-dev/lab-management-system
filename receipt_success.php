<?php
session_start();
include "db.php";

$username = $_SESSION['username'] ?? '';

// Check if receipt_id is provided in URL
if (!isset($_GET['receipt_id'])) {
    echo "No receipt found.";
    exit();
}

$receipt_id = (int)$_GET['receipt_id'];

// Fetch the receipt from the database
$receipt_result = mysqli_query($conn, "SELECT * FROM receipts WHERE receipt_id = $receipt_id LIMIT 1");
if (mysqli_num_rows($receipt_result) == 0) {
    echo "Receipt not found.";
    exit();
}
$receipt = mysqli_fetch_assoc($receipt_result);
$tests_result = mysqli_query($conn, "SELECT * FROM receipt_tests WHERE receipt_id = $receipt_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Bill</title>
    <link rel="stylesheet" href="styles.css"> <!-- External Stylesheet (Optional) -->
</head>
<body>

<!-- Header Section -->
<header>
    <img src="logo.png" alt="lab logo.png" style="width: 90px;margin-bottom: -30px;margin-top: -32px;">
    <h1>
        <a href="<?php echo isset($_SESSION['username']) ? 'dashboard.php' : 'login.php'; ?>" class="header-link">
            Cell Lab & Diagnostic Center
        </a>
    </h1>
    <?php if (!empty($username)) { ?>
        <h2 style="text-align: center;">Welcome, <?php echo ucfirst($username); ?>!</h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    <?php } ?>
</header>

<div class="patient-info" style="padding-top: 1px;">

<!-- Navigation Buttons -->
        <div class="navigation-buttons">
     
            <button onclick="window.location.href='add_patient.php'">🏠 Add Patient</button>
        </div>

<!-- Main Content -->
<div class="receipt-container">
    <div class="print-button">
        <button onclick="printReceipt()">🖨️ Print Receipt</button>
    </div>
    <br>
    <!-- Receipt Display Section -->
    <div class="receipt-box" id="receipt">
        <h2 class="center-text">Cell Lab & Diagnostic Center </h2>
        <p class="center-text">2nd Floor, Qazi Plaza, CMH Road Muzaffarabad AJK | Contact: 0347-0540453</p>
        <hr>

        <!-- Patient Info Table -->
        <table class="info-table">
            <tr>
                <th>Receipt No</th>
                <td><?= htmlspecialchars($receipt['receipt_no'] ?? 'N/A') ?></td>
                <th>MR No</th>
                <td><?= htmlspecialchars($receipt['mr_no'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th>Patient Name</th>
                <td><?= htmlspecialchars($receipt['patient_name'] ?? 'N/A') ?></td>
                <th>Gender</th>
                <td><?= htmlspecialchars($receipt['gender'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th>Age</th>
                <td><?= htmlspecialchars($receipt['age'] ?? 'N/A') ?> years</td>
                <th>Contact No</th>
                <td><?= htmlspecialchars($receipt['contact_no'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th>Date & Time</th>
                <td colspan="3">
                    <?php 
                    if (isset($receipt['payment_date']) && strtotime($receipt['payment_date']) > 0) {
                        echo date('d-m-Y H:i:s', strtotime($receipt['payment_date']));
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </td>
            </tr>
        </table>

        <!-- Tests Table -->
        <h4 class="center-text" style="margin-top: 30px;">Tests:</h4>
        <table class="test-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Test Name</th>
                    <th>Fee (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1; 
                $total = 0;
                while($test = mysqli_fetch_assoc($tests_result)): 
                    $total += $test['test_fee'];
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($test['test_name']) ?></td>
                    <td><?= number_format($test['test_fee'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="2" style="text-align:right;"><strong>Total Fee</strong></td>
                    <td><strong>Rs. <?= number_format($total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <p class="center-text" style="margin-top: 30px;">🧾 Thank you for your payment!</p>
        <p class="center-text">Sarosh Laboratory & Diagnostic Center | CMH Road, Muzaffarabad AJK</p>
        <p class="center-text">Contact: 0342-0792544</p>

        
    </div>
</div>
</div>
<!-- Footer Section -->
<footer>
        <p>Developed by Solutions Xpert &copy; <?php echo date('Y'); ?></p>
</footer>

<!-- Styles -->
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
}
header, footer {
    background-color: #2c3e50;
    color: #fff;
    padding: 20px;
    text-align: center;
}
header .header-container, footer .footer-container {
    max-width: 1200px;
    margin: 0 auto;
}
header .header-title {
    font-size: 2em;
    margin: 0;
}
header .header-subtitle {
    font-size: 1.2em;
    margin: 0;
}
.receipt-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
}
.receipt-box {
    background: #fff;
    padding: 30px;
    border: 1px solid #ccc;
}
.center-text {
    text-align: center;
}
.info-table, .test-table {
    width: 90%;
    margin: 20px 20px;
    border-collapse: collapse;
}
.info-table th, .info-table td,
.test-table th, .test-table td {
    border: 1px solid #000;
    padding: 8px 10px;
    text-align: left;
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
footer {
    background-color: #2c3e50;
    color: #fff;
    padding: 10px;
}
@media print {
    body * {
        visibility: hidden;
    }
    #receipt, #receipt * {
        visibility: visible;
    }
    #receipt {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
    .print-button {
        display: none;
    }
    .navigation-buttons {
        display: none;
    }
}

header {
    background-color: #3498db;
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}


.header h1 {
    color: white; /* Make h1 text white */
}

.header h2 {
    color: white; /* Make h1 text white */
}

.header-link {
    text-decoration: none; /* Remove underline */
    color: inherit; /* Inherit color from h1 (which is now white) */
}


/* Footer positioning and styling */
    footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        text-align: center;
        padding: 10px 0;
        background-color: #2c3e50; /* Dark background for the footer */
        color: white; /* Text color */
        font-size: 14px;
    }
	
	
	.patient-info {
            background: #f9f9f9;
            padding: 30px;
            margin: 20px 50px;
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
        }

.logout-btn {
    color: white;
    background: #e74c3c;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 5px;

</style>

<script>
function printReceipt() {
    window.print();
}
</script>

</body>
</html>
