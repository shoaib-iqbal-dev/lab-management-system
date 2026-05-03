<?php
session_start();
include "db.php";
include "header.php";
include "auth_check.php";

date_default_timezone_set("Asia/Karachi");


$patientData = null;
$billedTests = [];

// Handle Return request
if (isset($_POST['return_test'])) {
    $receipt_no = $_POST['receipt_no'];
    $test_name = $_POST['test_name'];
    $return_reason = mysqli_real_escape_string($conn, $_POST['return_reason']);
    $user_id = $_SESSION['user_id']; // assumes this is set during login

    // Get billed_fee and receipt_id from receipt_tests
    $feeResult = mysqli_query($conn, "SELECT billed_fee, receipt_id FROM receipt_tests WHERE receipt_no = '$receipt_no' AND test_name = '$test_name' LIMIT 1");
    $feeRow = mysqli_fetch_assoc($feeResult);
    $billed_fee = $feeRow['billed_fee'];
    $receipt_id = $feeRow['receipt_id'];

    // Get original fee from tests table
    $origResult = mysqli_query($conn, "SELECT fee FROM tests WHERE test_name = '$test_name' LIMIT 1");
    $original_fee = ($origRow = mysqli_fetch_assoc($origResult)) ? $origRow['fee'] : 0;

    $discount = $original_fee - $billed_fee;


// Get mr_no from receipts
$getMr = mysqli_query($conn, "SELECT mr_no FROM receipts WHERE receipt_id = $receipt_id");
$mrRow = mysqli_fetch_assoc($getMr);
$mr_no = $mrRow['mr_no']; // <-- should not be null


    // Save return info
    $insertReturn = "INSERT INTO returns (receipt_no, mr_no, test_name, billed_fee, original_fee, discount, returned_by, return_date, reason)
                 VALUES ('$receipt_no', '$mr_no', '$test_name', $billed_fee, $original_fee, $discount, $user_id, NOW(), '$return_reason')";

    mysqli_query($conn, $insertReturn);

    // Delete the test
    mysqli_query($conn, "DELETE FROM receipt_tests WHERE receipt_no = '$receipt_no' AND test_name = '$test_name'");

    // Deduct billed_fee from total_fee in receipts
    mysqli_query($conn, "UPDATE receipts SET total_fee = total_fee - $billed_fee WHERE receipt_id = $receipt_id");

    // Reload search with same MR No
	// Get MR No again safely
	$mrResult = mysqli_query($conn, "SELECT mr_no FROM receipts WHERE receipt_id = $receipt_id");
	if ($mrResult && mysqli_num_rows($mrResult) > 0) {
		$mrRow = mysqli_fetch_assoc($mrResult);
		$_SESSION['return_mr_no'] = $mrRow['mr_no'];  // store MR No in session
}

// Redirect to avoid re-posting on refresh
header("Location: returns.php");
exit();
}

// If return just happened and we stored MR No in session, use that
if (isset($_SESSION['return_mr_no'])) {
    $_POST['search'] = true;
    $_POST['mr_no'] = $_SESSION['return_mr_no'];
    unset($_SESSION['return_mr_no']); // clean up
}

// Search logic
if (isset($_POST['search'])) {
    $mr_no = $_POST['mr_no'];

    $patientQuery = "SELECT * FROM patients WHERE mr_no = '$mr_no'";
    $patientResult = mysqli_query($conn, $patientQuery);
    $patientData = mysqli_fetch_assoc($patientResult);

    $testQuery = "
    SELECT 
        rt.receipt_no,
        rt.test_name,
        IFNULL(t.fee, 'N/A') AS original_fee,
        rt.billed_fee,
        u.username AS billed_by,
        r.payment_date
    FROM receipt_tests rt
    JOIN receipts r ON rt.receipt_id = r.receipt_id
    JOIN users u ON r.billed_by = u.id
    LEFT JOIN tests t ON rt.test_name = t.test_name
    WHERE r.mr_no = '$mr_no'
    ORDER BY r.payment_date DESC
";

    $testResult = mysqli_query($conn, $testQuery);
    while ($row = mysqli_fetch_assoc($testResult)) {
        $billedTests[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Patient Billed Tests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f7fa;
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
            margin: 20px auto;
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
        .form-section {
            margin-top: 20px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: center;
        }
        .btn-delete {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .patient-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #f9f9f9;
        }
        .patient-details-table th,
        .patient-details-table td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .patient-details-table th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: 600;
        }
        .patient-details-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .patient-details-table tr:hover {
            background-color: #f1f1f1;
        }
        h3 {
            color: #f00;
            font-size: 18px;
            margin-top: 20px;
        }
        h3 strong {
            color: #007bff;
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
        .header h1, .header h2 {
            color: white;
        }
        .header-link {
            text-decoration: none;
            color: inherit;
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
    <a href="return_history.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back</a>
</div>

    <h2 style="text-align:center;">Search Patient by MR No</h2>
    <form method="POST" style="text-align:center;">
        <input type="text" name="mr_no" required placeholder="Enter MR No" />
        <button type="submit" name="search">Search</button>
    </form>

    <?php if ($patientData): ?>
        <h3 style="text-align: center; margin-top: 30px; color: #444;">Patient Information</h3>
        <table class="patient-details-table">
            <tr>
                <td><strong>MR No</strong></td>
                <td><?= $patientData['mr_no'] ?></td>
                <td><strong>Patient Name</strong></td>
                <td><?= $patientData['name'] ?></td>
            </tr>
            <tr>
                <td><strong>Gender</strong></td>
                <td><?= $patientData['gender'] ?></td>
                <td><strong>Age</strong></td>
                <td><?= $patientData['age'] ?></td>
            </tr>
            <tr>
                <td><strong>Contact No</strong></td>
                <td><?= $patientData['phone'] ?></td>
            </tr>
        </table>

        <h3 style="text-align:center;">Billed Tests</h3>
        <?php if (!empty($billedTests)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Receipt No</th>
                        <th>Test Name</th>
                        <th>Original Fee</th>
                        <th>Billed Fee</th>
                        <th>Billed By</th>
                        <th>Payment Date & Time</th>
                        <th>Return Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($billedTests as $test): ?>
                        <tr>
                            <td><?= $test['receipt_no'] ?></td>
                            <td><?= $test['test_name'] ?></td>
                            <td><?= $test['original_fee'] ?></td>
                            <td><?= $test['billed_fee'] ?></td>
                            <td><?= $test['billed_by'] ?></td>
                            <td><?= $test['payment_date'] ?></td>
                            <td>
                                <form method="POST" style="display:flex; align-items:center; gap:5px;">
                                    <input type="hidden" name="receipt_no" value="<?= $test['receipt_no'] ?>">
                                    <input type="hidden" name="test_name" value="<?= $test['test_name'] ?>">
                                    <input type="text" name="return_reason" placeholder="Reason" required style="padding:4px; width:120px;" />
                            </td>
                            <td>
                                    <button type="submit" name="return_test" onclick="return confirm('Are you sure you want to return this test?')">Return</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No billed tests found for this MR No.</p>
        <?php endif; ?>
    <?php elseif (isset($_POST['search'])): ?>
        <p>No patient found with that MR No.</p>
    <?php endif; ?>
</div>
<?php include "footer.php"; ?>
</body>
</html>
