<?php
session_start();
include "db.php";
include "header.php";
include "auth_check.php";

date_default_timezone_set("Asia/Karachi");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

$query = "
    SELECT SUM(rt.test_fee) AS total 
    FROM receipts r 
    JOIN receipt_tests rt ON r.receipt_id = rt.receipt_id 
    WHERE r.billed_by = ? AND DATE(r.payment_date) = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$stmt->bind_result($total_earning);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Today's Earnings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="dashboard">
    <h2>My Earnings for <?php echo $today; ?></h2>
    <div class="card">
        <h3>Rs <?php echo number_format($total_earning ?? 0); ?></h3>
    </div>
</main>
<?php include "footer.php"; ?>
</body>
</html>
