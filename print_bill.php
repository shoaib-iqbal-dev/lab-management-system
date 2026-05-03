<?php
session_start();
include "db.php";
include "header.php";
include "auth_check.php";

// Get MR No from URL
if (!isset($_GET['mr_no'])) {
    header("Location: search_patient.php");
    exit();
}

$mr_no = mysqli_real_escape_string($conn, $_GET['mr_no']);

// Get patient details
$patient_result = mysqli_query($conn, "SELECT * FROM patients WHERE mr_no = '$mr_no' LIMIT 1");
if (mysqli_num_rows($patient_result) == 0) {
    echo "Patient not found!";
    exit();
}
$patient = mysqli_fetch_assoc($patient_result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Bill - <?php echo htmlspecialchars($patient['name']); ?></title>
</head>
<body>

<h2>Bill for <?php echo htmlspecialchars($patient['name']); ?></h2>

<div>
    <h3>Selected Tests and Fees</h3>
    <table>
        <thead>
            <tr>
                <th>Test Name</th>
                <th>Fee (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Add tests and fees from session or database -->
        </tbody>
    </table>

    <div class="total-box">
        Total: Rs. <!-- Total fee here -->
    </div>

    <script>
        window.print(); // Automatically print the page when it loads
    </script>

</body>
</html>
