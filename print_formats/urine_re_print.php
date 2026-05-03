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
if (empty($receipt_id) || empty($mr_no) || strtolower($test_name) !== 'urine re') {
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
$testQuery = "SELECT urine_re.*, receipts.receipt_no, receipts.actual_fee, receipts.discount, receipts.total_fee, receipts.payment_date
              FROM urine_re
              LEFT JOIN receipts ON urine_re.receipt_id = receipts.receipt_id
              WHERE urine_re.receipt_id = ?";
$testStmt = $conn->prepare($testQuery);
$testStmt->bind_param("i", $receipt_id);
$testStmt->execute();
$testResult = $testStmt->get_result();
if ($testResult->num_rows === 0) {
    echo "<h3>No Urine RE result found for this receipt.</h3>";
    exit;
}
$row = $testResult->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Urine RE Report</title>
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
		.result-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .patient-info td {
            padding: 4px 5px;

        }

		.result-table th {
			border: 1px solid #333;
            padding: 5px;
           
		}			
		.result-table td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
			
			width:500px;
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
			padding: 05mm;
			box-sizing: border-box;
		}
    .report-footer, .report-footer * {
        visibility: visible;
    }

    .report-footer {
        position: fixed;
        bottom: 05mm;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 12px;
        padding: 0;
        margin: 0;
        width: 100%;
    }

    .report-footer hr {
        margin: 0 auto 5px auto;
        border: none;
        border-top: 1px solid #000;
        width: 100%;
    }

    .print-btn {
        display: none !important;
    }

    @page {
        size: A4;
        margin: 05mm;
    }
}

        .signature-line {
            text-align: right;
            margin-top: 60px;
            margin-right: 30px;
            font-size: 14px;
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
    <td rowspan="2" style="width: 40%;">
	<h3 style="margin-bottom: 0px;">Department of Microbiology</h3>
	</td>
	<td rowspan="2" style="width: 25%;">
	<p style="margin-bottom: 0px; font-size:19px;">Urine Examination</p>
	</td>
    <td style="font-size:12; width: 35%;"><b>Collection DateTime:</b> <?= date('d-m-Y h:i A', strtotime($row['payment_date'])) ?> </td>
  </tr>
  <tr>
    <td style="font-size:12; width: 35%;"><b>Reporting DateTime:</b> <?= date('d-m-Y h:i A', strtotime($row['reporting_datetime'])) ?></td>
  </tr>
</table>
<h3 style="text-align:center;margin-top: 0px;margin-bottom: 0px;"><u></u></h3>
<hr style="margin-top: 0px;margin-bottom: 0px;">
<table class="result-table" style="margin-bottom: 5px;margin-top: 0px;">   
    <tr>
	<th colspan="4" style="text-align:left; border-left:none; border-right:none;"><u>PHYSICAL EXAMINATION</u></th>
	</tr>
    <tr><td style="border-left:none; font-size:12px;">Colour</td><td style="font-size:12px;"><?= $row['colour'] ?></td>
    <td style="font-size:12px;">Turbidity/Deposit</td><td style="border-right:none; font-size:12px;"><?= $row['turbidity'] ?></td></tr>
	<tr><td style="border-left:none; font-size:12px;">Quantity</td><td style="font-size:12px;"><?= $row['quantity'] ?></td>
    <td style="font-size:12px;">Appearance</td><td style="border-right:none; font-size:12px;"><?= $row['appearance'] ?></td></tr>
</table>

<table class="result-table" style="margin-bottom: 5px; margin-top: 5px; border">
    <tr><th colspan="4" style="text-align:left; border-left:none; border-right:none;"><u>CHEMICAL EXAMINATION</u></th></tr>
<tr>
<td style="border-left:none; border-right:none;">
<table class="result-table" style="margin-bottom: 01px;margin-top: 0px; font-size:12px; "> 
<tr>
<th style="border-left:none; border-right:none; text-align:left;">Parameter name</th>
<th style="border-left:none; border-right:none;">Result</th>
<th style="border-left:none; border-right:none;">Reference Value</th>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Sp Gravity</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['sp_gravity'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">1.005-1.030</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">pH</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['ph'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">5.0-8.0</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Leukocyte Estrases</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['leukocyte'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Nitrite</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['nitrite'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Negative</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Proteins</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['protein'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
</tr>
</table>
</td>

<td style="border-left:none; border-right:none";>
<table class="result-table" style="margin-bottom: 01px;margin-top: 0px; font-size:12px;"> 
<tr>
<th style="border-left:none; border-right:none; text-align:left;">Parameter name</th>
<th style="border-left:none; border-right:none; text-align:center;">Result</th>
<th style="border-left:none; border-right:none; text-align:center;">Reference Value</th>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Sugar</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['sugar'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Ketones</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['ketones'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Urobilinogen</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['urobilinogen'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Bilirubin</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['bilirubin'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Heamoglobin</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['heamoglobin'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
</tr>
</table>
</td>
</tr>
</table>

<table class="result-table" style="margin-bottom: 5px; margin-top: 5px;">
    <tr><th colspan="4" style="text-align:left; border-left:none; border-right:none;"><u>MICROSCOPIC EXAMINATION</u></th></tr>
<tr>
<td style="border-left:none; border-right:none; border-bottom:none; ">
<table class="result-table" style="margin-bottom: 01px;margin-top: 0px; font-size:12px; "> 
<tr>
<th style="border-left:none; border-right:none; text-align:left;">Parameter name</th>
<th style="border-left:none; border-right:none;">Result</th>
<th style="border-left:none; border-right:none;">Reference Value</th>
<th style="border-left:none; border-right:none; text-align:center;">Unit</th>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Pus Cell/WBC</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['pus_cells'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">0-5</td>
<td style="border-left:none; border-right:none; text-align:center;">/HPF</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Red Blood Cell</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['rbc'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">0-5</td>
<td style="border-left:none; border-right:none; text-align:center;">/HPF</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Epithelial Cells</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['epithelial'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">0-5</td>
<td style="border-left:none; border-right:none; text-align:center;">/HPF</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Amorphous</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['amorphous'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
<td style="border-left:none; border-right:none; text-align:center;">/HPF</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Tyrosine Crystal</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['tyrosine_crystal'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
<td style="border-left:none; border-right:none; text-align:center;">/HPF</td>
</tr>
</table>
</td>

<td style="border-left:none; border-right:none; border-bottom:none; ">
<table class="result-table" style="margin-bottom: 01px;margin-top: 0px; font-size:12px;"> 
<tr>
<th style="border-left:none; border-right:none; text-align:left;">Parameter name</th>
<th style="border-left:none; border-right:none; text-align:center;">Result</th>
<th style="border-left:none; border-right:none; text-align:center;">Reference Value</th>
<th style="border-left:none; border-right:none; text-align:center;">Unit</th>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Calcium Oxalate</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['calcium_oxalate'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
<td style="border-left:none; border-right:none; text-align:center;">/HPF</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Yeast Cells</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['yeast_cells'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
<td style="border-left:none; border-right:none; text-align:center;">/HPF</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Dead Sperms</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['dead_sperms'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
<td style="border-left:none; border-right:none; text-align:center;">/HPF</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">Misc</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['misc'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
<td style="border-left:none; border-right:none; text-align:center;">/HPF</td>
</tr>
<tr>
<td style="border-left:none; border-right:none;">&nbsp</td>
<td style="border-left:none; border-right:none; text-align:center;"> </td>
<td style="border-left:none; border-right:none; text-align:center;"> </td>
<td style="border-left:none; border-right:none; text-align:center;"> </td>
</tr>
</table>
</td>
</tr>
<table class="result-table" style="margin-bottom: 01px;margin-top: 0px; font-size:12px;">
<tr>
<td colspan=2 style="border-left:none; border-right:none";>
<b>Casts</b>
</td>
</tr>
<tr>
<td style="border-left:none; border-right:none; border-bottom:none; ">
<table class="result-table" style="margin-bottom: 01px;margin-top: 0px; font-size:12px;"> 

<tr>
<td style="border-left:none; border-right:none;">Granular Casts</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['granular_cast'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
<td style="border-left:none; border-right:none; text-align:center;">/LPF</td>
</tr>

</table>
</td>
<td style="border-left:none; border-right:none; border-bottom:none; ">
<table class="result-table" style="margin-bottom: 01px;margin-top: 0px; font-size:12px;"> 

<tr>
<td style="border-left:none; border-right:none;">Hyaline Casts</td>
<td style="border-left:none; border-right:none; text-align:center;"><?= $row['hyaline_cast'] ?></td>
<td style="border-left:none; border-right:none; text-align:center;">Nil</td>
<td style="border-left:none; border-right:none; text-align:center;">/LPF</td>
</tr>

</table>
</td>
</tr>
</table>
</table>
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
