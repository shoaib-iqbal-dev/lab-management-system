<?php
session_start();
include "db.php";
include "header.php";
include "auth_check.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get MR No from URL
if (!isset($_GET['mr_no'])) {
    header("Location: search_patient.php");
    exit();
}

$mr_no = mysqli_real_escape_string($conn, $_GET['mr_no']);

// Fetch patient data
$query = "SELECT * FROM patients WHERE mr_no = '$mr_no' LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Patient not found!";
    exit();
}

$patient = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard - <?php echo htmlspecialchars($patient['name']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styling for patient dashboard */
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .patient-info {
            background: #fff;
            padding: 20px;
            margin: 30px auto;
            width: 95%;
            max-width: 1200px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        .patient-info h2 {
            margin-bottom: 20px;
            color: #3498db;
            text-align: center;
        }

        .patient-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
            background-color: white;
        }

        .patient-table th, .patient-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        .patient-table th {
            background-color: #3498db;
            color: white;
        }

        .patient-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .patient-table tr:hover {
            background-color: #ddd;
        }

        .dashboard-actions {
            text-align: center;
            margin-top: 30px;
        }

        .dashboard-actions a {
            display: inline-block;
            margin: 10px 15px;
            padding: 12px 25px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .dashboard-actions a:hover {
            background-color: #27ae60;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #3498db;
            color: white;
            margin-top: 40px;
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

<main>
    <div class="patient-info">
	
		<?php
// Assume you already get these in enter_result.php:
$mr_no = isset($_GET['mr_no']) ? $_GET['mr_no'] : '';
?>

<div style="text-align: center; margin-top: 20px;">
    <a href="search_patient.php" class="back-button" style="margin-bottom: 15px;margin-top: -20px;">Back to Patient list</a>
</div>

	
        <h2>Patient Details</h2>
        <table class="patient-table">
            <thead>
                <tr>
                    <th>MR No</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Referred By Doctor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($patient['mr_no']); ?></td>
                    <td><?php echo htmlspecialchars($patient['name']); ?></td>
                    <td><?php echo htmlspecialchars($patient['age']); ?> </td>
                    <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                    <td><?php echo htmlspecialchars($patient['address']); ?></td>
                    <td><?php echo htmlspecialchars($patient['phone']); ?></td>
                    <td><?php echo htmlspecialchars($patient['referred_by']); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="dashboard-actions">
            <a href="add_test.php?mr_no=<?php echo urlencode($patient['mr_no']); ?>">Add Test</a>
            <a href="previous_bills.php?mr_no=<?php echo urlencode($patient['mr_no']); ?>">Print Bill</a>
            <a href="upload_result.php?mr_no=<?php echo urlencode($patient['mr_no']); ?>" style="background-color: #e67e22;">Upload / View Result</a>
        </div>
    </div>
</main>

<?php include "footer.php"; ?>
</body>
</html>
