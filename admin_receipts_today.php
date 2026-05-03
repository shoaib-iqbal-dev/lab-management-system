<?php
include "db.php";
include "header.php";
include "auth_check.php";

date_default_timezone_set("Asia/Karachi");

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Get today's date in YYYY-MM-DD
$today_date = date('Y-m-d');

// Default to today's date if not set
$from_date = $_GET['from_date'] ?? date('Y-m-d');
$to_date = $_GET['to_date'] ?? date('Y-m-d');

// Sanitize inputs (basic)
$from_date = mysqli_real_escape_string($conn, $from_date);
$to_date = mysqli_real_escape_string($conn, $to_date);

$search = $_GET['search'] ?? '';
$search = mysqli_real_escape_string($conn, $search);


// SQL to get receipts in date range
$sql = "
    SELECT receipt_no, mr_no, patient_name, actual_fee, discount, total_fee, payment_date, gender, age, contact_no, billed_by, referred_by 
    FROM receipts 
    WHERE DATE(payment_date) BETWEEN '$from_date' AND '$to_date'
    ORDER BY payment_date DESC
";

$result = mysqli_query($conn, $sql);

// Total earnings in range
$total_sql = "
    SELECT SUM(total_fee) AS total_amount 
    FROM receipts 
    WHERE DATE(payment_date) BETWEEN '$from_date' AND '$to_date'
";

$total_result = mysqli_query($conn, $total_sql);
$total_amount = 0;
if ($total_result && mysqli_num_rows($total_result) > 0) {
    $row = mysqli_fetch_assoc($total_result);
    $total_amount = $row['total_amount'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Today's Receipts (Admin View)</title>
    <style>
			   body {
			font-family: Arial, sans-serif;
			margin: 0;
			background: #f5f7fa;
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
    <a href="daily_summary.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back</a>
</div>
        <h2 style="text-align:center;">Receipts Details</h2>

<form method="GET" action="">
    <label for="from_date">From:</label>
    <input type="date" name="from_date" value="<?php echo $from_date; ?>" required>

    <label for="to_date">To:</label>
    <input type="date" name="to_date" value="<?php echo $to_date; ?>" required>

    <button type="submit">Filter</button>
</form>
<h3 style="text-align:center;">Receipts from <?php echo date('d-M-Y', strtotime($from_date)); ?> to <?php echo date('d-M-Y', strtotime($to_date)); ?></h3>


        <table>
            <thead>
                <tr>
                    <th>Receipt No</th>
                    <th>MR No</th>
                    <th>Patient Name</th>
					 <th>Gender</th>
                    <th>Age</th>
                    <th>Contact No</th>
					<th>Payment Date</th>
                    <th>Actual Fee</th>
                    <th>Discount</th>
                    <th>Billed Fee</th>
                    <th>Referred By</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['receipt_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['mr_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
							<td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
							<td><?php echo date('d-M-Y h:i A', strtotime($row['payment_date'])); ?></td>
                            <td>Rs <?php echo number_format($row['actual_fee']); ?></td>
                            <td>Rs <?php echo number_format($row['discount']); ?></td>
                            <td>Rs <?php echo number_format($row['total_fee']); ?></td>
                            <td><?php echo htmlspecialchars($row['referred_by']); ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="11" style="text-align:center;">No receipts found for today.</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
				<td colspan="7"></td>
                    <td colspan="2" class="total-cell" style="text-align:center;">Total Amount:</td>
                    <td colspan="2" style="text-align:left;">Rs <?php echo number_format($total_amount); ?></td>
                    
                </tr>
            </tfoot>
        </table>
    </div>
<?php include "footer.php"; ?>
</body>
</html>
