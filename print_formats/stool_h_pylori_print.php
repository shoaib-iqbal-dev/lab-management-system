<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db.php'; // your database connection

// Get values from query parameters
$receipt_id = isset($_GET['receipt_id']) ? $_GET['receipt_id'] : '';
$mr_no = isset($_GET['mr_no']) ? $_GET['mr_no'] : '';
$test_name = isset($_GET['test_name']) ? $_GET['test_name'] : '';

// Validate input
if (empty($receipt_id) || empty($mr_no) || strtolower($test_name) !== 'stool h pylori') {
    echo "<h3>Invalid request or test format not available.</h3>";
    exit;
}

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user full name from the database (assuming there is a 'users' table)
    $userQuery = "SELECT username FROM users WHERE username = ?";
    $userStmt = $conn->prepare($userQuery);
    if (!$userStmt) {
        die("Prepare failed for user query: " . $conn->error);
    }
    $userStmt->bind_param("s", $username);
    $userStmt->execute();
    $userResult = $userStmt->get_result();

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        $printed_by = $user['username']; // Store the full name
    } else {
        $printed_by = 'Unknown'; // Default to 'Unknown' if user is not found
    }
} else {
    $printed_by = 'Unknown'; // Default if user is not logged in
}

// Get patient info
$patientQuery = "SELECT name, age, gender, phone, referred_by FROM patients WHERE mr_no = ?";
$patientStmt = $conn->prepare($patientQuery);
if (!$patientStmt) {
    die("Prepare failed for patient query: " . $conn->error);
}
$patientStmt->bind_param("s", $mr_no);
$patientStmt->execute();
$patientResult = $patientStmt->get_result();
if ($patientResult->num_rows === 0) {
    echo "<h3>Patient not found.</h3>";
    exit;
}
$patient = $patientResult->fetch_assoc();

// Get test results along with receipt_no from the receipts table
$testQuery = "SELECT stool_h_pylori.*, receipts.receipt_no, receipts.actual_fee, receipts.discount, receipts.total_fee, receipts.payment_date
              FROM stool_h_pylori
              LEFT JOIN receipts ON stool_h_pylori.receipt_id = receipts.receipt_id
              WHERE stool_h_pylori.receipt_id = ?";
$testStmt = $conn->prepare($testQuery);
$testStmt->bind_param("i", $receipt_id);
$testStmt->execute();
$testResult = $testStmt->get_result();
if ($testResult->num_rows === 0) {
    echo "<h3>No Stool H Pylori result found for this receipt.</h3>";
    exit;
}
$row = $testResult->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Stool H Pylori Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .lab-header {
            margin-bottom: 30px;
            margin-top: 0px;
        }

        .lab-header img {
            max-height: 100px;
        }

        .lab-header h1 {
            font-size: 70px;
			font-family: times new roman ;
            margin: 0; /* Remove the default margin from h2 */
        }

         .patient-info {
			 width: 100%;
            margin-bottom: 20px;
		 }
		.patient-info td {
            padding: 4px 5px;
        }	
		
		.result-table {
			width: 98%;
			font-size: 10px;
			border-collapse: collapse;
			
		  }

		.result-table th {
		 border: 1px solid #ccc; /* light grey lines */
			text-align: left;
			padding: 6px 8px;
			color:black;
			 background-color: transparent;
			
		}
		.result-table td {
			border-top: 1px solid #ccc;  /* Light grey horizontal line */
			border-bottom: 1px solid #ccc;
			
			text-align: left;
			padding: 6px 8px;
		  }

        .result-table {
            border-collapse: collapse;
        }

        .print-btn {
            margin-top: 20px;
            text-align: center;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }

        .info-table, .test-table {
            width: 100%;
            margin: 20px 20px;
            border-collapse: collapse;
        }

        .info-table th, .info-table td,
        .test-table th, .test-table td {
            border: 1px solid #000;
            padding: 5px 5px;
            text-align: left;
        }

        .test-table th {
            background-color: #f0f0f0;
        }

        .total-row {
            background-color: #e0ffe0;
        }
		
		.vertical-text {
            font-size: 10px;
            writing-mode: sideways-lr;
        }

@media print {
    body * {
        visibility: hidden;
    }

    #report, #report * {
        visibility: visible;
    }
	 #report {
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			padding: 5mm;
			box-sizing: border-box;
			
		}


    .print-btn {
        display: none !important;
    }

    @page {
        size: A4;
        margin: 05mm;
    }
}

        .patient-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 15px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        .patient-table th {
            background-color: #f0f0f0;
            text-align: left;
            padding: 5px;
            width: 15%;
            border: 1px solid #ccc;
            font-weight: bold;
        }

        .patient-table td {
            padding: 5px;
            width: 35%;
            border: 1px solid #ccc;
        }
		.overlay-text {
		  position: absolute; /* or relative, depending on your container */
		  top: 50%; 
		  right: 0;
		  transform: translateY(-50%) rotate(-90deg);
		  transform-origin: right center;

		  background: transparent; /* fully transparent */
		  color: black; /* or whatever color you want */
		  padding: 0; /* no padding for overlay */
		  font-size: 18px;
		  font-weight: bold;
		  white-space: nowrap;
		  z-index: 9999;
		}


    </style>
</head>
<body>
<div id="report" style="margin-top: -10px;">

<div class="lab-header" style="margin-bottom: 15px;">
    <img src="loggo.png" alt="Lab Logo"style="margin-bottom: -5px; margin-top: 0px;">
</div>
<p style="text-align:center;margin-top: -25px;margin-bottom: 0px;">The Facility For All Routine And Special Test Are Available</p>
<hr style="margin-top: 0px;margin-bottom: 0px;">
<p style="text-align:right;margin-top: 0px;margin-bottom: 0px;">ACCURACY & QUALITY ASSURED</p>

<table class="patient-table" style="margin-bottom: 0px;margin-top: 0px; text-align">
    <tr>
        <th>Name</th>
        <td><?= htmlspecialchars($patient['name']) ?></td>
        <th>MR No</th>
        <td><?= htmlspecialchars($mr_no) ?></td>
    </tr>
    <tr>
        <th>Gender</th>
        <td><?= htmlspecialchars($patient['gender']) ?></td>
        <th>Age</th>
        <td><?= htmlspecialchars($patient['age']) ?></td>
    </tr>
    <tr>
        <th>Phone</th>
        <td><?= htmlspecialchars($patient['phone']) ?></td>
          <th>Referred By</th>
        <td><?= htmlspecialchars($patient['referred_by']) ?></td>
		
    </tr>
    <tr>
      <th>Printed By</th>
        <td><?= htmlspecialchars($printed_by) ?></td>
        
    </tr>
</table>
<hr style="margin-top: 0px;margin-bottom: 0px;">
<table width="100%">
  <tr>
    <td rowspan="2" style="width: 65%;">
	<h3 style="margin-bottom: 0px;">Department of Serology</h3>
	</td>
    <td style="font-size:12; width: 35%;"><b>Collection DateTime:</b> <?= date('d-m-Y h:i A', strtotime($row['payment_date'])) ?> </td>
  </tr>
  <tr>
    <td style="font-size:12; width: 35%;"><b>Reporting DateTime:</b> <?= date('d-m-Y h:i A', strtotime($row['reporting_datetime'])) ?></td>
  </tr>
</table>
<hr style="margin-top: 0px;margin-bottom: 0px;">
<p style="margin-bottom: 0px;margin-top: 0px;"><b>Stool for H. Pylori Agent</b></p>

<table class="result-table" style="margin-top: 0px;margin-bottom: 10px; font-size:12">
    
<tr>
        <th style="width: 40%; border-left: none; border-top: none; border-bottom: none;"></th>
        <th style="width: 32%; border-top: none; border-bottom: none; border-right: none;"><h1><?= $row['stool_h_pylori'] ?></h1></th>
    </tr>
	<tr>
		<td style="width: 40%; border-left: none; border-top: none;"> .</td>
		<td style="border-top: none; border-right: none;"></td>
		<td style="border-left: none; border-right: none; border-top: none; "></td>
		<td style="border-left: none; border-right: none; border-top: none; "></td>
		<td style="border-left: none; border-right: none; border-top: none; "></td>
		<td style="width: 40%; border-left: none; border-right: none; border-top: none;"></td>
   
</table>
<hr style="margin-top: 0px;margin-bottom: 0px;">

<br>

<div class="overlay-text" style="margin-right: 20px;font-size:8px;left: 400px;">Electronically verified report. No signature required. Lab reports should be interpreted by a physician in correlation with clinical and radiologic findings</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div style="font-size:12;text-align:center;">
<hr style="margin-top: -10px; margin-bottom: 05px;">
    <p style="margin-bottom: 0px;margin-top: 0px;">2nd Floor, Qazi Plaza, CMH Road Muzaffarabad AJK&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  Phone: 0347-0540453 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  Not Valid for Court or Medico Legal Use</p>
	</div>	
	<div class="print-button" style="text-align:center;margin-top: 0px;">
    <button onclick="window.print()">🖨️ Print Report</button>
	</div>
</div>
</body>
</html>
