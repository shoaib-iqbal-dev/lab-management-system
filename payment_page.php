<?php
session_start();
include "db.php";
include "header.php";

// Redirect if not logged in
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

// Get patient details
$patient_result = mysqli_query($conn, "SELECT * FROM patients WHERE mr_no = '$mr_no' LIMIT 1");
if (mysqli_num_rows($patient_result) == 0) {
    echo "Patient not found!";
    exit();
}
$patient = mysqli_fetch_assoc($patient_result);

// Get selected tests and fees from POST request
$test_names = $_POST['test_names'];
$test_fees = $_POST['test_fees'];

// Save tests and fees to the database after payment (optional)
foreach ($test_names as $index => $test_name) {
    $test_fee = $test_fees[$index];
    $query = "INSERT INTO patient_tests (mr_no, test_name, test_fee) VALUES ('$mr_no', '$test_name', '$test_fee')";
    mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation - <?php echo htmlspecialchars($patient['name']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Payment Confirmation for <?php echo htmlspecialchars($patient['name']); ?></h2>

<div>
    <h3>Selected Tests</h3>
    <table>
        <thead>
            <tr>
                <th>Test Name</th>
                <th>Fee (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($test_names as $index => $test_name): ?>
                <tr>
                    <td><?php echo htmlspecialchars($test_name); ?></td>
                    <td><?php echo htmlspecialchars($test_fees[$index]); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-box">
        Total: Rs. <?php echo array_sum($test_fees); ?>
    </div>

    <form method="POST" action="print_bill.php?mr_no=<?php echo $mr_no; ?>">
        <button type="submit" class="btn">Print Bill</button>
    </form>
</div>

</body>
</html>

<?php include "footer.php"; ?>
