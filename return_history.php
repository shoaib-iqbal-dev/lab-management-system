<?php
session_start();
include "db.php";
include "header.php";

date_default_timezone_set("Asia/Karachi");


// Fetch return records with user info
$query = "
    SELECT r.*, u.username 
    FROM returns r
    JOIN users u ON r.returned_by = u.id
    ORDER BY r.return_date DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return History</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            margin: 0;
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
		
		header {
    background-color: #3498db;
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
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

.action-btn {
            background-color: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .action-btn:hover {
            background-color: #27ae60;
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
<div class="table-container" style="margin-top: 20px;">

<div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back to Dashboard</a>
</div>	

    <h2 style="text-align:center;">Return History</h2>
	<div style="text-align:center;"><a href="returns.php" class="action-btn">Add Return</a></div>
	
    <table>
        <thead>
            <tr>
                <th>Receipt No</th>
                <th>Test Name</th>
                <th>Billed Fee</th>
                <th>Returned By</th>
                <th>Return Date</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['receipt_no'] ?></td>
                    <td><?= $row['test_name'] ?></td>
                    <td><?= $row['billed_fee'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['return_date'] ?></td>
                    <td><?= $row['reason'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
	</div>
	<?php include "footer.php"; ?>
</body>
</html>
