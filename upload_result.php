<?php
session_start();
include "db.php";
include "header.php";
include "auth_check.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get MR No from the URL
if (!isset($_GET['mr_no'])) {
    header("Location: search_patient.php");
    exit();
}

$mr_no = mysqli_real_escape_string($conn, $_GET['mr_no']);

// Fetch the tests for the current page
$tests_query = "
    SELECT rt.receipt_id, t.test_name, r.payment_date, r.receipt_no
    FROM receipt_tests rt
    JOIN receipts r ON rt.receipt_id = r.receipt_id
    JOIN tests t ON rt.test_name = t.test_name
    WHERE r.mr_no = '$mr_no'
";
$tests_result = mysqli_query($conn, $tests_query);

if (!$tests_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Function to check if result exists in the test table
function resultExists($conn, $test_name, $receipt_id) {
    $table_name = strtolower(str_replace(" ", "_", $test_name));
    $check_table_query = "SHOW TABLES LIKE '$table_name'";
    $table_result = mysqli_query($conn, $check_table_query);
    if (mysqli_num_rows($table_result) == 0) {
        return false;
    }
    $check_result_query = "SELECT * FROM $table_name WHERE receipt_id = '$receipt_id'";
    $result = mysqli_query($conn, $check_result_query);
    return mysqli_num_rows($result) > 0;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Test Results</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        h2 {
            text-align: center;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
            display: inline-block;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn.disabled {
            background-color: #aaa;
            cursor: not-allowed;
            pointer-events: none;
        }

        .search-bar {
            text-align: center;
            margin: 20px 0;
        }

        .search-bar input {
            padding: 8px;
            margin: 5px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 12px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
        }

        .pagination a.disabled {
            background-color: #aaa;
            cursor: not-allowed;
        }

        .pagination a:hover {
            background-color: #45a049;
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
    <div class="table-container">
	
		<?php
// Assume you already get these in enter_result.php:
$mr_no = isset($_GET['mr_no']) ? $_GET['mr_no'] : '';
?>

<div style="text-align: center; margin-top: 20px;">
    <a href="patient_dashboard.php?mr_no=<?php echo urlencode($mr_no); ?>" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back</a>
</div>

	
        <h2>Upload Test Results for MR No: <?php echo htmlspecialchars($mr_no); ?></h2>

        <div class="search-bar">
            <input type="text" id="testInput" onkeyup="filterTable()" placeholder="Search by Test Name">
            <input type="text" id="receiptInput" onkeyup="filterTable()" placeholder="Search by Receipt No">
            <input type="text" id="dateInput" onkeyup="filterTable()" placeholder="Search by Date">
        </div>

        <?php
        if (mysqli_num_rows($tests_result) > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Test Name</th>';
            echo '<th>Receipt No</th>';
            echo '<th>Bill Date</th>';
            echo '<th>Result Status</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = mysqli_fetch_assoc($tests_result)) {
                $test_name = $row['test_name'];
                $receipt_id = $row['receipt_id'];
                $payment_date = $row['payment_date'];
                $receipt_no = $row['receipt_no'];

                $has_result = resultExists($conn, $test_name, $receipt_id);

                echo '<tr>';
                echo '<td>' . htmlspecialchars($test_name) . '</td>';
                echo '<td>' . htmlspecialchars($receipt_no) . '</td>';
                echo '<td>' . htmlspecialchars($payment_date) . '</td>';
                echo '<td>' . ($has_result ? "Uploaded" : "Pending") . '</td>';
                echo '<td>';
                
                // Upload/Edit button with dynamic label
                $editLabel = $has_result ? 'Edit Result' : 'Upload Result';
                echo '<a href="enter_result.php?receipt_id=' . $receipt_id . '&mr_no=' . urlencode($mr_no) . '&test_name=' . urlencode($test_name) . '" class="btn">' . $editLabel . '</a>';

                // Print button enabled only if result exists
                if ($has_result) {
                    echo '<a href="print_result.php?receipt_id=' . $receipt_id . '&mr_no=' . urlencode($mr_no) . '&test_name=' . urlencode($test_name) . '" class="btn">Print</a>';
                } else {
                    echo '<a class="btn disabled">Print (No Results)</a>';
                }

                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo "<p>No billed tests found for this patient.</p>";
        }
        ?>
    </div>
</main>

<script>
    function filterTable() {
        const testInput = document.getElementById("testInput").value.toUpperCase();
        const receiptInput = document.getElementById("receiptInput").value.toUpperCase();
        const dateInput = document.getElementById("dateInput").value.toUpperCase();

        const rows = document.querySelectorAll("table tbody tr");

        rows.forEach(row => {
            const testCell = row.cells[0].textContent.toUpperCase();
            const receiptCell = row.cells[1].textContent.toUpperCase();
            const dateCell = row.cells[2].textContent.toUpperCase();

            if (
                testCell.indexOf(testInput) > -1 &&
                receiptCell.indexOf(receiptInput) > -1 &&
                dateCell.indexOf(dateInput) > -1
            ) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
</script>

</body>
</html>
