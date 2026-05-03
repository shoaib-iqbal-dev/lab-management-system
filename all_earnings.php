<?php
session_start();
include "db.php";
include "header.php";
include "auth_check.php";

date_default_timezone_set("Asia/Karachi");

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle date filter input
$from = $_GET['from'] ?? date('Y-m-d');
$to = $_GET['to'] ?? date('Y-m-d');

// Get all users
$usersQuery = "SELECT id, username FROM users";
$usersResult = mysqli_query($conn, $usersQuery);

// Get earnings from `receipts` table by billed_by
$earningsQuery = "
    SELECT billed_by, SUM(total_fee) as total_earning
    FROM receipts
    WHERE DATE(payment_date) BETWEEN '$from' AND '$to'
    GROUP BY billed_by";
$earningsResult = mysqli_query($conn, $earningsQuery);

// Check for query errors
if (!$usersResult || !$earningsResult) {
    echo "Error: " . mysqli_error($conn);
    exit();
}

// Map earnings to billed_by user ID
$earningsData = [];
while ($row = mysqli_fetch_assoc($earningsResult)) {
    $earningsData[$row['billed_by']] = $row['total_earning'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users Earnings</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .table-container {
        width: 95%;
        margin: 20px auto;
        background-color: #fff;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
	
	
	
    h2 {
        text-align: center;
        margin-top: 20px;
    }
    .filter-form {
        text-align: center;
        margin: 20px 0;
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

        th, td {
            padding: 10px;
            text-align: left;
        }
	
    .print-btn {
        display: block;
        margin: 20px auto;
        padding: 6px 12px;
        font-size: 14px;
        text-align: center;
        background-color: #007bff;
        color: white;
        border: none;
        text-decoration: none;
        border-radius: 4px;
        cursor: pointer;
    }

    @media print {
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
        }
		@page {
        size: A4;
        margin: 05mm;
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

<main class="allearningtable">
    <div class="table-container" style="margin-top: 30px;padding-bottom: 30px;">

<div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back to Dashboard</a>
</div>	
	
        <h2 style="margin-top: 0px;margin-bottom: 20px;padding-top: 10px;">Earnings Breakdown by User</h2>

        <form class="filter-form" method="GET">
            From: <input type="date" name="from" value="<?php echo $from; ?>" required>
            To: <input type="date" name="to" value="<?php echo $to; ?>" required>
            <button type="submit">Filter</button>
        </form>

        <button onclick="printSection()" class="print-btn">Print</button>

        <div id="print-section">
            <p style="text-align:center; font-weight: bold; margin-top: 10px;">
                Earnings from <?php echo date("F j, Y", strtotime($from)); ?> to <?php echo date("F j, Y", strtotime($to)); ?>
            </p>

            <?php if (mysqli_num_rows($usersResult) > 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Total Earnings (Rs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grandTotal = 0;
                        while ($user = mysqli_fetch_assoc($usersResult)) {
                            $userId = $user['id'];
                            $userName = htmlspecialchars($user['username']);
                            $userEarnings = isset($earningsData[$userId]) ? $earningsData[$userId] : 0;
                            $grandTotal += $userEarnings;
                            echo "<tr>
                                    <td>$userName</td>
                                    <td>" . number_format($userEarnings, 2) . "</td>
                                </tr>";
                        }
                        ?>
                        <tr style="font-weight: bold; background-color: #e0f7e0;">
                            <td>Total</td>
                            <td><?php echo number_format($grandTotal, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php } else { ?>
                <p style="text-align: center;">No users found.</p>
            <?php } ?>
        </div>
    </div>
</main>

<script>
function printSection() {
    window.print();
}
</script>

</body>
</html>

<?php include "footer.php"; ?>
