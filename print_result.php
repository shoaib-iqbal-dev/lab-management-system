<?php
session_start();
include "header.php"; // Optional: include your page header
include "auth_check.php";

// Validate URL parameters
if (!isset($_GET['receipt_id']) || !isset($_GET['mr_no']) || !isset($_GET['test_name'])) {
    echo "Invalid Request.";
    exit;
}

// Get parameters
$receipt_id = $_GET['receipt_id'];
$mr_no = $_GET['mr_no'];
$test_name = $_GET['test_name'];

// Clean and format test_name to match file naming
$formatted_test_name = strtolower(str_replace(' ', '_', $test_name));
$print_file = "print_formats/" . $formatted_test_name . "_print.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Test Report</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional: link to your CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
		
           
        }
        .print-area {
            border: 1px solid #ccc;
            padding: 20px;
        }
        .print-button {
            margin-top: 20px;
        }
        @media print {
            .print-button {
                display: none;
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
<body style="margin-top: 0px;margin-bottom: 0px;margin-right: 0px;margin-left: 0px;">

<h2 style="text-align:center;">Test Report: <?php echo htmlspecialchars($test_name); ?></h2>

<?php
// Assume you already get these in enter_result.php:
$mr_no = isset($_GET['mr_no']) ? $_GET['mr_no'] : '';
?>

<div style="text-align: center; margin-top: 20px;">
    <a href="upload_result.php?mr_no=<?php echo urlencode($mr_no); ?>" class="back-button" style="margin-bottom: 0px;">Back to Test List</a>
</div>

<div class="print-area">
    <?php
    if (file_exists($print_file)) {
        include $print_file;
    } else {
        echo "<p style='color: red;'>No format available for this test.</p>";
    }
    ?>
</div>



</body>
</html>
