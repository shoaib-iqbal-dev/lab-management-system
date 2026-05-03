<?php
require 'vendor/autoload.php'; // DOMPDF autoloader
include "db.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$fromDate = $_POST['from_date'] ?? date('Y-m-d');
$toDate = $_POST['to_date'] ?? date('Y-m-d');

// Get all users
$usersQuery = "SELECT id, username FROM users";
$usersResult = mysqli_query($conn, $usersQuery);

// Get earnings data
$earningsQuery = "
    SELECT pt.billed_by, SUM(pt.test_fee) as total_earning
    FROM patient_tests pt
    WHERE DATE(pt.billed_at) BETWEEN '$fromDate' AND '$toDate'
    GROUP BY pt.billed_by
";
$earningsResult = mysqli_query($conn, $earningsQuery);

// Prepare earnings array
$earningsData = [];
while ($row = mysqli_fetch_assoc($earningsResult)) {
    $earningsData[$row['billed_by']] = $row['total_earning'];
}

// Build HTML
$html = "
    <h2 style='text-align:center;'>Earnings Report<br>($fromDate to $toDate)</h2>
    <table border='1' cellspacing='0' cellpadding='8' width='100%'>
        <thead>
            <tr>
                <th>User</th>
                <th>Total Earnings (Rs)</th>
            </tr>
        </thead>
        <tbody>
";

while ($user = mysqli_fetch_assoc($usersResult)) {
    $userId = $user['id'];
    $username = htmlspecialchars($user['username']);
    $earning = isset($earningsData[$userId]) ? $earningsData[$userId] : 0;
    $html .= "
        <tr>
            <td>{$username}</td>
            <td>" . number_format($earning, 2) . "</td>
        </tr>
    ";
}

$html .= "</tbody></table>";

// Generate PDF
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("earnings_report_$fromDate-to-$toDate.pdf", ["Attachment" => true]);
exit;
