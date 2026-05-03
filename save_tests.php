<?php
session_start();
include "db.php";

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get form data
$mr_no = mysqli_real_escape_string($conn, $_POST['mr_no']);
$selected_tests = json_decode($_POST['selected_tests'], true);
$total_fee = mysqli_real_escape_string($conn, $_POST['total_fee']);
$billed_by = $_SESSION['username']; // Assuming the username is stored in session
$billed_at = date("Y-m-d H:i:s");

// Insert each selected test into the database
foreach ($selected_tests as $test_name) {
    // Get the test fee from the database
    $test_result = mysqli_query($conn, "SELECT fee FROM tests WHERE test_name = '$test_name' LIMIT 1");
    if (mysqli_num_rows($test_result) == 0) {
        echo "Test not found: $test_name";
        exit();
    }
    $test = mysqli_fetch_assoc($test_result);
    $test_fee = $test['fee'];

    // Insert the test into the patient_tests table
    $query = "INSERT INTO patient_tests (mr_no, test_name, test_fee, billed_by, billed_at, date_added) 
              VALUES ('$mr_no', '$test_name', '$test_fee', '$billed_by', '$billed_at', NOW())";
    if (!mysqli_query($conn, $query)) {
        echo "Error inserting test: " . mysqli_error($conn);
        exit();
    }
}

// Optionally, you could update the total fee for the patient in the patients table (if needed)
$update_query = "UPDATE patients SET total_fee = total_fee + '$total_fee' WHERE mr_no = '$mr_no'";
mysqli_query($conn, $update_query);

// Redirect to a confirmation page or back to the patient details page
header("Location: patient_details.php?mr_no=$mr_no");
exit();
?>
