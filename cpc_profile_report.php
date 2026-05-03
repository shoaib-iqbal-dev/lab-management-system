<?php

date_default_timezone_set("Asia/Karachi");

// Get the receipt_id from the URL
$receipt_id = $_GET['receipt_id'];  // Pass the receipt ID from the previous page or URL

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "lab_system");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch the test result based on receipt_id
$sql = "SELECT * FROM `cpc_profile` WHERE receipt_id = '$receipt_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // Extract values from the result
    $haemoglobin = $row['haemoglobin'];
    $rbc = $row['rbc'];
    $haematocrit = $row['haematocrit'];
    $mcv = $row['mcv'];
    $mch = $row['mch'];
    $mchc = $row['mchc'];
    $white_cells = $row['white_cells'];
    $neutrophils = $row['neutrophils'];
    $lymphocytes = $row['lymphocytes'];
    $monocytes = $row['monocytes'];
    $eosinophils = $row['eosinophils'];
    $basophils = $row['basophils'];
    $platelet_count = $row['platelet_count'];
    $esr = $row['esr'];
} else {
    echo "No data found!";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPC Profile Test Report</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h2>CPC Profile Test Report</h2>
    <table>
        <tr>
            <th>Test Parameter</th>
            <th>Result</th>
        </tr>
        <tr>
            <td>Haemoglobin</td>
            <td><?php echo $haemoglobin; ?></td>
        </tr>
        <tr>
            <td>RBC</td>
            <td><?php echo $rbc; ?></td>
        </tr>
        <tr>
            <td>Haematocrit</td>
            <td><?php echo $haematocrit; ?></td>
        </tr>
        <!-- Add rows for all other test parameters -->
    </table>
    <button onclick="window.print()">Print Report</button>
</body>
</html>
