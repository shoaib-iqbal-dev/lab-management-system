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
if (empty($receipt_id) || empty($mr_no) || strtolower($test_name) !== 'urea') {
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
$testQuery = "SELECT urea.*, receipts.receipt_no, receipts.actual_fee, receipts.discount, receipts.total_fee, receipts.payment_date
              FROM urea
              LEFT JOIN receipts ON urea.receipt_id = receipts.receipt_id
              WHERE urea.receipt_id = ?";
$testStmt = $conn->prepare($testQuery);
$testStmt->bind_param("i", $receipt_id);
$testStmt->execute();
$testResult = $testStmt->get_result();
if ($testResult->num_rows === 0) {
    echo "<h3>No UREA result found for this receipt.</h3>";
    exit;
}
$row = $testResult->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>UREA Report</title>
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

		.chart-wrapper {
		  position: relative;
		  height: 100px;
		  width: 180px;
		  border: 1px solid #aaa;
		  background: white;
		  overflow: visible; /* allow overflow for y-scale outside */
		}

		.y-scale {
		  position: absolute;
		  left: -30px;  /* outside left */
		  top: 0;
		  bottom: 0;
		  width: 20px;
		  display: flex;
		  flex-direction: column;
		  justify-content: space-between;
		  font-size: 10px;
		  text-align: right;
		  padding-right: 4px;
		}


		.chart-lines {
		  position: absolute;
		  left: 0;  /* changed from 20px to 0 */
		  right: 0;
		  top: 0;
		  bottom: 0;
		  display: flex;
		  flex-direction: column;
		  justify-content: space-between;
		}

		  .chart-lines div {
			border-top: 1px solid #aaa;
			height: 100%;
		  }

.marker {
  position: absolute;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #222;
  color: #fff;
  padding: 2px 5px;
  font-size: 12px;
  border-radius: 3px;

  /* These 2 lines make sure color shows in print */
  -webkit-print-color-adjust: exact;
  print-color-adjust: exact;
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
	<h3 style="margin-bottom: 0px;">Renal Function Test</h3>
	</td>
    <td style="font-size:12; width: 35%;"><b>Collection DateTime:</b> <?= date('d-m-Y h:i A', strtotime($row['payment_date'])) ?> </td>
  </tr>
  <tr>
    <td style="font-size:12; width: 35%;"><b>Reporting DateTime:</b> <?= date('d-m-Y h:i A', strtotime($row['reporting_datetime'])) ?></td>
  </tr>
</table>
<hr style="margin-top: 0px;margin-bottom: 0px;">

<table class="result-table" style="margin-top: 3px; margin-bottom: 10px; font-size:12px; width: 100%; border-collapse: collapse;">
  <tr>
    <th style="width: 37%; border: none; vertical-align: top; text-align: left;">Serum Urea</th>
    <th style="border: none;"width: 35%;">
      <div class="chart-wrapper" id="serum_ureaChart">
        <div class="y-scale" style="height: 110px;top: -5px;">
          <div>65</div>
          <div></div>
          <div>55</div>
          <div></div>
		  <div>45</div>
          <div></div>
          <div>35</div>
          <div></div>
          <div>25</div>
		  <div></div>
          <div>15</div>
		  <div></div>
          <div>05</div>
        </div>
        <div class="chart-lines">
          <div style="border-top-width: 0px;"></div><div></div><div></div><div></div><div></div><div></div>
        </div><!-- Marker will be inserted dynamically --></div></th>
		<th style="width: 28%; border: none; text-align: left;">
	  <div style="font-size: 55px; line-height: 1; font-weight: 300; font-family: 'Arial', sans-serif;">
  <?= $row['serum_urea'] ?><sub style="font-size: 16px; vertical-align: sub;">mg/dl</sub>
	  </div><div style="font-size: 18px; font-weight: bold; margin-left: 40px;">
  <?php
    if ($row['serum_urea'] < 10) {
        echo 'Low';
    } elseif ($row['serum_urea'] <= 50) {
        echo 'Normal';
    } else {
        echo 'High';
    }
  ?>
</div>

	  </th>
  </tr>
  <tr>
    <td style="border: none;"></td>
    <td style="border: none; font-size: 10px; text-align:right;">
      <b>• Low (<10) &nbsp&nbsp• Normal (10 - 50) &nbsp&nbsp• High (>50)</b>
    </td>
	<td style="border: none;"></td>
  </tr>
</table>





<hr style="margin-top: 0px;margin-bottom: 0px;">
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
<div style="font-size:12;text-align:center;">
<hr style="margin-top: -10px; margin-bottom: 05px;">
    <p style="margin-bottom: 0px;margin-top: 0px;">2nd Floor, Qazi Plaza, CMH Road Muzaffarabad AJK&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  Phone: 0347-0540453 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  Not Valid for Court or Medico Legal Use</p>
	</div>	
	<div class="print-button" style="text-align:center;margin-top: 0px;">
    <button onclick="window.print()">🖨️ Print Report</button>
	</div>
</div>
</body>

<script>
const serum_ureaValue = parseFloat("<?= $row['serum_urea'] ?>");
const ureaChart = document.getElementById('serum_ureaChart');

const ureaMin = 05;
const ureaMax = 65;

if (!isNaN(serum_ureaValue)) {
  let percentage = 100 - ((serum_ureaValue - ureaMin) / (ureaMax - ureaMin)) * 100;

  // Clamp percentage inside 0 to 100
  percentage = Math.max(0, Math.min(100, percentage));

  const marker = document.createElement('div');
  marker.className = 'marker';
  marker.style.top = `${percentage}%`;
  marker.innerText = serum_ureaValue.toFixed(1);

  ureaChart.appendChild(marker);
}



</script>

</html>