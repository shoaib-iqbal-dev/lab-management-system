<?php
session_start();
include "db.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receipt_test_id = mysqli_real_escape_string($conn, $_POST['receipt_test_id']);
    $test_id = mysqli_real_escape_string($conn, $_POST['test_id']);

    // Loop through the form fields and save the data
    $fields_query = "
        SELECT * 
        FROM test_form_fields 
        WHERE test_id = '$test_id'
    ";
    $fields_result = mysqli_query($conn, $fields_query);

    if (!$fields_result) {
        die("Error fetching form fields: " . mysqli_error($conn));
    }

    // Prepare an array to store the result data
    $result_data = [];
    while ($field = mysqli_fetch_assoc($fields_result)) {
        $field_name = $field['field_name'];
        if (isset($_POST[$field_name])) {
            $result_data[$field_name] = mysqli_real_escape_string($conn, $_POST[$field_name]);
        }
    }

    // Save the result in the database
    $columns = implode(", ", array_keys($result_data));
    $values = "'" . implode("', '", $result_data) . "'";

    $query = "
        INSERT INTO test_results (receipt_test_id, $columns)
        VALUES ('$receipt_test_id', $values)
    ";

if (mysqli_query($conn, $query)) {
    header("Location: enter_result.php?saved=1"); // ← REDIRECT with param
    exit();
} else {
    echo "Error saving test result: " . mysqli_error($conn);
}

}
?>
