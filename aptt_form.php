<?php
// Set your timezone (adjust if needed)
date_default_timezone_set("Asia/Karachi");

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "lab_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$success = "";
$error_message = "";
$receipt_id = isset($_GET['receipt_id']) ? intval($_GET['receipt_id']) : 0;

// Ensure a valid receipt_id is provided
if ($receipt_id <= 0) {
    die("❌ Invalid or missing receipt ID.");
}

// Determine if we are in edit mode: check if a record exists
$edit_mode = false;
$existing_data = ['pt' => '', 'pt_control' => '', 'aptt' => '', 'aptt_control' => '', 'inr' => ''];
$fetch_sql = "SELECT * FROM aptt WHERE receipt_id = $receipt_id";
$fetch_result = mysqli_query($conn, $fetch_sql);
if (mysqli_num_rows($fetch_result) > 0) {
    $edit_mode = true;
    $existing_data = mysqli_fetch_assoc($fetch_result);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input data
    $pt_value  = mysqli_real_escape_string($conn, $_POST['pt']);
	$pt_control_value  = mysqli_real_escape_string($conn, $_POST['pt_control']);
	$aptt_value = mysqli_real_escape_string($conn, $_POST['aptt']);
	$aptt_control_value = mysqli_real_escape_string($conn, $_POST['aptt_control']);
    $inr_value = mysqli_real_escape_string($conn, $_POST['inr']);

    if ($edit_mode) {
        // Update existing record and auto-set reporting_datetime to current time using NOW()
        $update_sql = "UPDATE aptt SET 
                            pt = '$pt_value',
							pt_control = '$pt_control_value',
							aptt = '$aptt_value',
							aptt_control = '$aptt_control_value',
                            inr = '$inr_value', 
                            reporting_datetime = NOW() 
                       WHERE receipt_id = $receipt_id";
        if (mysqli_query($conn, $update_sql)) {
            $success = "Record updated successfully.";
            // Re-fetch updated data from database
            $fetch_result = mysqli_query($conn, $fetch_sql);
            $existing_data = mysqli_fetch_assoc($fetch_result);
        } else {
            $error_message = "Error updating record: " . mysqli_error($conn);
        }
    } else {
        // Insert new record and set reporting_datetime automatically with NOW()
        $insert_sql = "INSERT INTO aptt (receipt_id, pt, pt_control, aptt, aptt_control, inr, reporting_datetime) 
                       VALUES ($receipt_id, '$pt_value', '$pt_control_value', '$aptt_value', '$aptt_control_value', '$inr_value', NOW())";
        if (mysqli_query($conn, $insert_sql)) {
            $success = "Record inserted successfully.";
            $edit_mode = true;
            // Re-fetch newly inserted data
            $fetch_result = mysqli_query($conn, $fetch_sql);
            $existing_data = mysqli_fetch_assoc($fetch_result);
        } else {
            $error_message = "Error inserting record: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>APTT Test Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 { 
		text-align: center; 
		margin-bottom: 20px;
		}
        label { 
		display: block; 
		font-weight: bold; 
		margin-top: 10px; 
		}
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .message {
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
        }
        .success { color: green; }
        .error { color: red; }
    button { 
		width: 100%; 
		padding: 12px; 
		background: #007bff; 
		color: white; 
		border: none; 
		border-radius: 5px; 
		font-size: 16px; 
		cursor: pointer; 
		margin-top: 20px; 
		}
    button:hover { 
		background: #0056b3;
		}
		.reporting-info { margin-top: 20px; font-size: 14px; color: #333; text-align: center; }

    </style>
</head>
<body>
<div class="container" style="margin-bottom: 100px;">
    <h2>APTT Test Report</h2>
    
    <?php if (!empty($success)) : ?>
        <p class="message success"><?= $success; ?></p>
    <?php endif; ?>
    
    <?php if (!empty($error_message)) : ?>
        <p class="message error"><?= $error_message; ?></p>
    <?php endif; ?>
    
    <form method="post">
        <label for="pt">PT:</label>
        <input type="text" id="pt" name="pt" required value="<?= htmlspecialchars($existing_data['pt']); ?>">
		
		<label for="pt_control">PT Control:</label>
        <input type="text" id="pt_control" name="pt_control" required value="<?= htmlspecialchars($existing_data['pt_control']); ?>">

		
        <label for="aptt">APTT:</label>
        <input type="text" id="aptt" name="aptt" required value="<?= htmlspecialchars($existing_data['aptt']); ?>">

		<label for="aptt_control">APTT Control:</label>
        <input type="text" id="aptt_control" name="aptt_control" required value="<?= htmlspecialchars($existing_data['aptt_control']); ?>">


        <label for="inr">INR:</label>
        <input type="text" id="inr" name="inr" required value="<?= htmlspecialchars($existing_data['inr']); ?>">

        <button type="submit"><?= $edit_mode ? "Update Result" : "Submit Result" ?></button>
    </form>
	
		<?php if (!empty($existing_data['reporting_datetime'])): ?>
            <div class="reporting-info">
                <p><strong>Last Reported:</strong> <?= $existing_data['reporting_datetime'] ?></p>
            </div>
        <?php endif; ?>
	
</div>
</body>
</html>

<?php mysqli_close($conn); ?>