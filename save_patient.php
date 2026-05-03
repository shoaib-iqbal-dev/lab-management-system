<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form values
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Insert into database
    $sql = "INSERT INTO patients (name, age, gender, phone, address) VALUES ('$name', '$age', '$gender', '$phone', '$address')";

    if (mysqli_query($conn, $sql)) {
        // Redirect to dashboard or show success
        header("Location: dashboard.php?message=Patient added successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: add_patient.php");
    exit();
}
?>
