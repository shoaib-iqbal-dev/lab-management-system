<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $query = "DELETE FROM lab_expenses WHERE id = $id";
    mysqli_query($conn, $query);
}

header("Location: add_expense.php"); // Change to your page filename
exit();
?>

