<?php
session_start();
include "db.php";
include "header.php";

date_default_timezone_set("Asia/Karachi");


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

// Fetch patient info
$patientQuery = "SELECT * FROM patients WHERE mr_no = '$mr_no'";
$patientResult = mysqli_query($conn, $patientQuery);
$patient = mysqli_fetch_assoc($patientResult);

// Fetch tests
$testQuery = "SELECT * FROM tests WHERE mr_no = '$mr_no' ORDER BY test_date DESC";
$testResult = mysqli_query($conn, $testQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Tests - MR No: <?php echo htmlspecialchars($mr_no); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Extra styling for table */
        .tests-container {
            width: 80%;
            margin: 30px auto;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
        }
        .tests-container h2 {
            color: #3498db;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .no-tests {
            text-align: center;
            color: #e74c3c;
            margin-top: 20px;
            font-weight: bold;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #2ecc71;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-btn:hover {
            background: #27ae60;
        }
    </style>
</head>
<body>

<header>
   
</header>

<main>
    <div class="tests-container">
        <h2>Tests for <?php echo htmlspecialchars($patient['name']); ?> (MR No: <?php echo htmlspecialchars($mr_no); ?>)</h2>

        <?php if (mysqli_num_rows($testResult) > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Test ID</th>
                        <th>Test Name</th>
                        <th>Test Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($testResult)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['test_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['test_date']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="no-tests">No tests found for this patient.</p>
        <?php } ?>

        <a href="search_patient.php" class="back-btn">Back to Search</a>
    </div>
</main>

<footer>
    <p>Lab Management System &copy; <?php echo date('Y'); ?></p>
</footer>

</body>
</html>
