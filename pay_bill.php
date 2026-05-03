<?php
session_start();
include "db.php";
include "header.php";

// Redirect if not logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Get the data from the POST request
$mr_no = mysqli_real_escape_string($conn, $_POST['mr_no']);
$selected_tests = json_decode($_POST['selected_tests']); // Get selected tests as an array
$actual_fee = $_POST['actual_fee'];
$discount = $_POST['discount'];
$total_fee = $_POST['total_fee'];
$doctor_id = isset($_POST['doctor_id']) ? mysqli_real_escape_string($conn, $_POST['doctor_id']) : null;
$referred_by = isset($_POST['referred_by']) ? mysqli_real_escape_string($conn, $_POST['referred_by']) : '';

// Step 1: Generate the receipt number
$date_part = date('y') . date('n') . date('j'); // e.g., 2553 for May 3, 2025

// Count existing receipts for today
$query = "SELECT COUNT(*) as count FROM receipts WHERE receipt_no LIKE '{$date_part}%'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$sequence = $row['count'] + 1;

// Pad the sequence with leading zeros
$sequence_str = str_pad($sequence, 4, '0', STR_PAD_LEFT); // e.g., 0001

// Combine date part and sequence to create receipt_no
$receipt_no = $date_part . $sequence_str;

// Step 2: Fetch the patient's name and other info
$query_patient = "SELECT name, gender, age, phone FROM patients WHERE mr_no = '$mr_no' LIMIT 1";
$result_patient = mysqli_query($conn, $query_patient);
$patient = mysqli_fetch_assoc($result_patient);

if (!$patient) {
    echo "Patient not found.";
    exit();
}

$patient_name = $patient['name'];

// Step 3: Insert into receipts table (now includes doctor_id and referred_by)
$query = "INSERT INTO receipts 
    (receipt_no, patient_name, mr_no, referred_by, actual_fee, discount, total_fee, payment_date, gender, age, contact_no, billed_by, doctor_id) 
    VALUES 
    ('$receipt_no', '$patient_name', '$mr_no', '$referred_by', '$actual_fee', '$discount', '$total_fee', NOW(), 
    '{$patient['gender']}', '{$patient['age']}', '{$patient['phone']}', '$user_id', '$doctor_id')";

if (mysqli_query($conn, $query)) {
    // Get the newly created receipt_id
    $receipt_id = mysqli_insert_id($conn);

    // Calculate discount percentage
    $discount_percentage = ($discount / $actual_fee) * 100;

    // Insert selected tests into receipt_tests with discounted fee
    foreach ($selected_tests as $test_name) {
        $test_result = mysqli_query($conn, "SELECT fee FROM tests WHERE test_name = '$test_name' LIMIT 1");
        $test = mysqli_fetch_assoc($test_result);
        $test_fee = $test['fee'];

        // Calculate discounted fee
        $discounted_fee = $test_fee - ($test_fee * ($discount_percentage / 100));
        $discounted_fee = round($discounted_fee, 2); // Round to 2 decimal places

        $test_discount = $test_fee - $discounted_fee;

        $insert_test_query = "INSERT INTO receipt_tests (receipt_id, test_name, test_fee, billed_fee, test_discount, receipt_no) 
                              VALUES ('$receipt_id', '$test_name', '$test_fee', '$discounted_fee', '$test_discount', '$receipt_no')";

        mysqli_query($conn, $insert_test_query);
    }

    // Step 4: Insert doctor commission if doctor_id is provided
    if (!empty($doctor_id)) {
        $commission_query = "SELECT commission_percentage FROM doctors WHERE id = '$doctor_id' LIMIT 1";
        $commission_result = mysqli_query($conn, $commission_query);
        if ($commission_row = mysqli_fetch_assoc($commission_result)) {
            $percentage = $commission_row['commission_percentage'];
            $commission_amount = ($percentage / 100) * $total_fee;

            $insert_commission = "INSERT INTO doctor_commissions (doctor_id, receipt_id, commission_amount) 
                                  VALUES ('$doctor_id', '$receipt_id', '$commission_amount')";
            mysqli_query($conn, $insert_commission);
        }
    }

    // Redirect to print page
    header("Location: print_receipt.php?receipt_id=$receipt_id");
    exit();
} else {
    echo "Error inserting receipt: " . mysqli_error($conn);
}
?>
