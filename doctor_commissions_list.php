<?php
session_start();
include "db.php";
include "auth_check.php";

date_default_timezone_set("Asia/Karachi");

// Redirect if not logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch doctors for dropdown
$doctors = mysqli_query($conn, "SELECT id, name FROM doctors ORDER BY name ASC");

// Handle filters
$doctor_filter = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Fetch commission total with filters
$conditions = [];
if (!empty($doctor_filter)) {
    $conditions[] = "dc.doctor_id = '" . mysqli_real_escape_string($conn, $doctor_filter) . "'";
}
if (!empty($start_date)) {
    $conditions[] = "DATE(r.payment_date) >= '" . mysqli_real_escape_string($conn, $start_date) . "'";
}
if (!empty($end_date)) {
    $conditions[] = "DATE(r.payment_date) <= '" . mysqli_real_escape_string($conn, $end_date) . "'";
}

$whereClause = '';
if (!empty($conditions)) {
    $whereClause = "WHERE " . implode(' AND ', $conditions);
}

$totalQuery = "SELECT SUM(dc.commission_amount) AS total_commission
               FROM doctor_commissions dc
               JOIN receipts r ON dc.receipt_id = r.receipt_id
               $whereClause";

$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$total_commission = $totalRow['total_commission'] ? number_format($totalRow['total_commission'], 2) : "0.00";

// Fetch commission details for table display
$query = "SELECT dc.commission_amount, r.receipt_no, r.payment_date, d.name AS doctor_name, r.mr_no
          FROM doctor_commissions dc
          JOIN doctors d ON dc.doctor_id = d.id
          JOIN receipts r ON dc.receipt_id = r.receipt_id
          $whereClause
          ORDER BY r.payment_date DESC";

;


$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Commissions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f3;
            margin: 0;
            padding: 0;
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

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
            align-items: center;
            justify-content: center;
        }

        form label {
            font-weight: bold;
        }

        form select,
        form input[type="date"] {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            padding: 8px 16px;
            background: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        

        table tr:hover {
            background-color: #f5f5f5;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #888;
        }

      header {
    background-color: #3498db;
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
		
		.header-link {
    text-decoration: none; /* Remove underline */
    color: inherit; /* Inherit color from h1 (which is now white) */
}

.logout-btn {
    color: white;
    background: #e74c3c;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 5px;
}



        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 12px;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 40px;
        }
		
.center-btn-container {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.add-btn {
    background-color: #28a745;
    color: white;
    padding: 8px 14px;
    text-decoration: none;
    border-radius: 4px;
    font-weight: bold;
    text-align: center;
}

.add-btn:hover {
    background-color: #218838;
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

<?php include "header.php"; ?>

<div class="table-container">

<div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php" class="back-button" style="margin-bottom: 20px;margin-top: -50px;">Back to Dashboard</a>
</div>

    <h2 style="text-align: center";>Commissions</h2>

    <div class="center-btn-container">
        <a href="doctor_commission.php" class="add-btn">See/Add Commission</a>
    </div>
    <br>

    <form method="GET">
        <label for="doctor_id">Name:</label>
        <select name="doctor_id" id="doctor_id">
            <option value="">All</option>
            <?php while ($doc = mysqli_fetch_assoc($doctors)) { ?>
                <option value="<?= $doc['id'] ?>" <?= $doctor_filter == $doc['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($doc['name']) ?>
                </option>
            <?php } ?>
        </select>

        <label for="start_date">From:</label>
        <input type="date" name="start_date" value="<?= $start_date ?>">

        <label for="end_date">To:</label>
        <input type="date" name="end_date" value="<?= $end_date ?>">

        <button type="submit">Filter</button>
    </form>

<table>
    <thead>
        <tr>
			<th>S.No</th>
            <th>Name</th>
            <th>MR No</th>
            <th>Receipt No</th>
            <th>Payment Date & Time</th> <!-- Updated column for date and time -->
            <th>Commission (Rs)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        $serial = 1;
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $total += $row['commission_amount'];
                echo "<tr>";
				echo "<td>" . $serial++ . "</td>";
                echo "<td>" . htmlspecialchars($row['doctor_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['mr_no']) . "</td>"; 
                echo "<td>" . htmlspecialchars($row['receipt_no']) . "</td>";
                echo "<td>" . htmlspecialchars(date('Y-m-d H:i:s', strtotime($row['payment_date']))) . "</td>";
                echo "<td>" . number_format($row['commission_amount'], 2) . "</td>";
                echo "</tr>";
            }

            // Show total row
            echo "<tr style='font-weight:bold; background-color:#f9f9f9'>";
            echo "<td></td>";
            echo "<td></td>";
			echo "<td></td>";
			echo "<td>Total</td>";
            echo "<td>Rs " . number_format($total, 2) . "</td>";
            echo "</tr>";
        } else {
            echo "<tr><td colspan='5' class='no-data'>No commissions found for selected filters.</td></tr>";
        }
        ?>
    </tbody>
</table>


    
</div>

<?php include "footer.php"; ?>

</body>
</html>