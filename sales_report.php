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

$from = $_GET['from'] ?? date('Y-m-d');
$to = $_GET['to'] ?? date('Y-m-d');

// Updated to use receipts table
$earningsQuery = "SELECT SUM(total_fee) as total FROM receipts WHERE DATE(payment_date) BETWEEN '$from' AND '$to'";
$earningsResult = mysqli_query($conn, $earningsQuery);
$earningRow = mysqli_fetch_assoc($earningsResult);
$earning = $earningRow['total'] ?? 0;

$expenseQuery = "SELECT SUM(amount) as total FROM lab_expenses WHERE DATE(expense_date) BETWEEN '$from' AND '$to'";
$expenseResult = mysqli_query($conn, $expenseQuery);
$expenseRow = mysqli_fetch_assoc($expenseResult);
$expense = $expenseRow['total'] ?? 0;

$profit = $earning - $expense;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report - Lab Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .patient-info {
            background: #f9f9f9;
            padding: 30px;
            margin: 20px 50px;
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
        }

        .report-table {
            margin: 20px auto;
            width: 90%;
            border-collapse: collapse;
            background: #fff;
        }

        .report-table th, .report-table td {
            padding: 12px 20px;
            border: 1px solid #333;
            text-align: center;
        }

        .report-table th {
            background: #f0f0f0;
        }

        .filter-form {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-form input,
        .filter-form button {
            padding: 6px 10px;
            margin: 5px;
        }

        .print-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .lab-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }

        .date-range-heading {
            text-align: center;
            font-size: 18px;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        /* Print Styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            body * {
                visibility: hidden;
            }

            #print-section, #print-section * {
                visibility: visible;
            }

            #print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20mm;
                box-sizing: border-box;
            }

            .report-table {
                width: 100%;
                border: 1px solid #333;
                border-collapse: collapse;
            }

            .report-table th,
            .report-table td {
                border: 1px solid #333 !important;
                padding: 10px;
            }

            .lab-header {
                font-size: 26px;
                font-weight: bold;
                margin-bottom: 5mm;
            }

            .date-range-heading {
                font-size: 18px;
                margin-bottom: 10mm;
            }

            .print-btn, .filter-form, .add-expense-link {
                display: none !important;
            }
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

<div class="patient-info">

<div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back to Dashboard</a>
</div>


    <h2 style="text-align:center;">Sales & Expense Report</h2>

    <!-- Filter Form -->
    <form class="filter-form" method="GET">
        From: <input type="date" name="from" value="<?php echo $from; ?>" required>
        To: <input type="date" name="to" value="<?php echo $to; ?>" required>
        <button type="submit">Filter</button>
    </form>

    <!-- Add Expense Button -->
    <div style="text-align: center;" class="add-expense-link">
        <a href="add_expense.php" style="padding: 8px 16px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;">➕ Add / See Expense</a>
    </div>

    <!-- Print Button -->
    <div style="text-align: center;">
        <button onclick="printReport()" class="print-btn">🖨️ Print Report</button>
    </div>

    <!-- Printable Section -->
    <div id="print-section">
        <div class="lab-header">Cell Lab</div>
        <div class="date-range-heading">
            Report from <strong><?php echo date('d M Y', strtotime($from)); ?></strong>
            to <strong><?php echo date('d M Y', strtotime($to)); ?></strong>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Total Earnings (Rs)</th>
                    <th>Total Expenses (Rs)</th>
                    <th>Net Profit (Rs)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo number_format($earning, 2); ?></td>
                    <td><?php echo number_format($expense, 2); ?></td>
                    <td><?php echo number_format($profit, 2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function printReport() {
    window.print();
}
</script>

</body>
</html>

<?php include "footer.php"; ?>
