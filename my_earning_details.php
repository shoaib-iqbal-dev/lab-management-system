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
$user_id = $_SESSION['user_id'];

$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

// Validate and format dates
$from_date_sql = $from_date ? date('Y-m-d', strtotime($from_date)) : date('Y-m-d');
$to_date_sql = $to_date ? date('Y-m-d', strtotime($to_date)) : date('Y-m-d');

// Prepare SQL query to get receipts of current user filtered by date range
$sql = "
    SELECT receipt_id, receipt_no, mr_no, actual_fee, discount, patient_name, total_fee, payment_date, gender, age, contact_no 
    FROM receipts 
    WHERE billed_by = '$user_id' 
      AND DATE(payment_date) BETWEEN '$from_date_sql' AND '$to_date_sql'
    ORDER BY payment_date DESC
";

$result = mysqli_query($conn, $sql);

// Calculate total sum for displayed receipts
$total_sum_sql = "
    SELECT SUM(total_fee) AS total_amount 
    FROM receipts 
    WHERE billed_by = '$user_id' 
      AND DATE(payment_date) BETWEEN '$from_date_sql' AND '$to_date_sql'
";
$total_result = mysqli_query($conn, $total_sum_sql);
$total_amount = 0;
if ($total_result && mysqli_num_rows($total_result) > 0) {
    $row = mysqli_fetch_assoc($total_result);
    $total_amount = $row['total_amount'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Earnings Details</title>
    <style>
        /* Updated CSS for vertical layout */
        body {
            font-family: Arial, sans-serif;
            
            background: #f9f9f9;
        }
		
		header {
		background-color: #3498db;
		color: white;
		padding: 15px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-top: -8px;
		margin-left: -8px;
		margin-right: -8px;
		margin-bottom: -8px;
		}

	.logout-btn {
		color: white;
		background: #e74c3c;
		padding: 8px 12px;
		text-decoration: none;
		border-radius: 5px;
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
			
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            margin-right: 5px;
        }
        input[type="date"] {
            padding: 5px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 8px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
.table-container {
            width: 95%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
		
        table {
            width: 90%;
            margin: 20px auto; /* Center the table horizontally */
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }
        tfoot tr td {
            font-weight: bold;
            background-color: #f1f1f1;
            text-align: right;
        }
        .total-cell {
            text-align: left;
            padding-left: 10px;
        }
        @media (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                display: none;
            }
            tr {
                margin-bottom: 15px;
            }
            td {
                border: none;
                position: relative;
                padding-left: 50%;
            }
            td::before {
                position: absolute;
                top: 12px;
                left: 10px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
                content: attr(data-label);
            }
            tfoot tr td {
                text-align: left;
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
<div class="table-container" style="margin-top: 35px;">

<div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back to Dashboard</a>
</div>

    <h1>My Earnings Details</h1>

    <form method="GET" action="">
        <div>
            <label for="from_date">From Date:</label>
            <input type="date" id="from_date" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>" max="<?php echo date('Y-m-d'); ?>" />
        </div>
        <div>
            <label for="to_date">To Date:</label>
            <input type="date" id="to_date" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>" max="<?php echo date('Y-m-d'); ?>" />
        </div>
        <button type="submit">Filter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Receipt No</th>
                <th>MR No</th>
                <th>Patient Name</th>
                <th>Actual Fee</th>
                <th>Discount</th>
                <th>Total Fee</th>
                <th>Payment Date</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Contact No</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td data-label="Receipt No"><?php echo htmlspecialchars($row['receipt_no']); ?></td>
                        <td data-label="MR No"><?php echo htmlspecialchars($row['mr_no']); ?></td>
                        <td data-label="Patient Name"><?php echo htmlspecialchars($row['patient_name']); ?></td>
                        <td data-label="Actual Fee">Rs <?php echo number_format($row['actual_fee']); ?></td>
                        <td data-label="Discount">Rs <?php echo number_format($row['discount']); ?></td>
                        <td data-label="Total Fee">Rs <?php echo number_format($row['total_fee']); ?></td>
                        <td data-label="Payment Date"><?php echo date('d-M-Y h:i A', strtotime($row['payment_date'])); ?></td>
                        <td data-label="Gender"><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td data-label="Age"><?php echo htmlspecialchars($row['age']); ?></td>
                        <td data-label="Contact No"><?php echo htmlspecialchars($row['contact_no']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="10" style="text-align:center;">No receipts found for the selected date range.</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="total-cell">Total Amount:</td>
                <td>Rs <?php echo number_format($total_amount); ?></td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>
</div>
<?php include "footer.php"; ?>
</body>
</html>
