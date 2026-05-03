<?php
session_start();
include "db.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receipt_test_id = mysqli_real_escape_string($conn, $_POST['receipt_test_id']);
    $test_name = mysqli_real_escape_string($conn, $_POST['test_name']);
    
    // Get the data posted for the test columns
    $columns = [];
    foreach ($_POST as $key => $value) {
        if ($key != 'receipt_test_id' && $key != 'test_name') {
            $columns[$key] = mysqli_real_escape_string($conn, $value);
        }
    }
    
    // Create the column names and values
    $column_names = implode(", ", array_keys($columns));
    $column_values = "'" . implode("', '", array_values($columns)) . "'";

    // Insert data into the corresponding test table
    $insert_query = "INSERT INTO `" . $test_name . "` ($column_names, receipt_test_id) VALUES ($column_values, '$receipt_test_id')";

    if (mysqli_query($conn, $insert_query)) {
        echo "<script>alert('Test result saved successfully!'); window.location = 'upload_result.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
