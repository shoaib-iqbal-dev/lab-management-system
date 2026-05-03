<?php
session_start();
include "db.php";
include "header.php";

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

// Fetch receipts data
$query = "SELECT * FROM receipts WHERE mr_no = '$mr_no' ORDER BY payment_date DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Query failed: " . mysqli_error($conn);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Previous Bills - <?php echo htmlspecialchars($mr_no); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #3498db;
            margin-top: 30px;
        }

        .receipt-table-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }

        .receipt-table th,
        .receipt-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .receipt-table th {
            background-color: #3498db;
            color: white;
        }

        .receipt-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .receipt-table tr:hover {
            background-color: #f1f1f1;
        }

        .receipt-table td a {
            display: inline-block;
            padding: 8px 15px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .receipt-table td a:hover {
            background-color: #27ae60;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #3498db;
            color: white;
            margin-top: 30px;
        }

        /* Print style */
        @media print {
            footer {
                display: none;
            }
            .receipt-table-container {
                max-width: 100%;
                margin: 0;
                padding: 0;
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

<main>
    <div class="receipt-table-container">
	
	<?php
// Assume you already get these in enter_result.php:
$mr_no = isset($_GET['mr_no']) ? $_GET['mr_no'] : '';
?>

<div style="text-align: center; margin-top: 20px;">
    <a href="patient_dashboard.php?mr_no=<?php echo urlencode($mr_no); ?>" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back</a>
</div>
	
        <h2>Previous Bills for Patient: <?php echo htmlspecialchars($mr_no); ?></h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Receipt No</th>
                        <th>Payment Date and Time</th>
                        <th>Total Fee (Rs.)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($receipt = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($receipt['receipt_no']); ?></td>
                            <td><?php echo date("d-m-Y H:i:s", strtotime($receipt['payment_date'])); ?></td>
                            <td><?php echo number_format($receipt['total_fee'], 2); ?></td>
                            <td>
                                <!-- Print Button -->
                                <a href="javascript:void(0);" onclick="printReceipt(<?php echo $receipt['receipt_id']; ?>)">Print</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #e74c3c;">No previous bills found for this patient.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Your Hospital Name</p>
</footer>

<script>
    function printReceipt(receipt_id) {
        // Open a new window to display the print version of the receipt
        var printWindow = window.open('print_receipt.php?receipt_id=' + receipt_id, '_blank');
        printWindow.focus();
    }
</script>

</body>
</html>

<?php include "footer.php"; ?>
