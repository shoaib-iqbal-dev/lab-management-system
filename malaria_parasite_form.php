<?php
date_default_timezone_set("Asia/Karachi");
$conn = mysqli_connect("localhost", "root", "", "lab_system");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$success = false;
$error_message = "";
$edit_mode = false;

$receipt_id = isset($_GET['receipt_id']) ? intval($_GET['receipt_id']) : 0;

if ($receipt_id <= 0) {
    $error_message = "❌ Invalid or missing receipt ID.";
} else {
    $check_sql = "SELECT * FROM malaria_parasite WHERE receipt_id = $receipt_id";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $edit_mode = true;
        $existing_data = mysqli_fetch_assoc($check_result);
    } else {
        $existing_data = ['malaria_parasite_pv' => '','malaria_parasite_pf' => '', 'reporting_datetime' => ''];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $malaria_parasite_pv = mysqli_real_escape_string($conn, $_POST['malaria_parasite_pv']);
    $malaria_parasite_pf = mysqli_real_escape_string($conn, $_POST['malaria_parasite_pf']);

    if ($edit_mode) {
        $update_sql = "UPDATE malaria_parasite SET malaria_parasite_pv = '$malaria_parasite_pv',malaria_parasite_pf = '$malaria_parasite_pf', reporting_datetime = NOW() WHERE receipt_id = $receipt_id";
        if (mysqli_query($conn, $update_sql)) {
            $success = true;
            $check_result = mysqli_query($conn, $check_sql);
            $existing_data = mysqli_fetch_assoc($check_result);
        } else {
            $error_message = "Error updating record: " . mysqli_error($conn);
        }
    } else {
        $insert_sql = "INSERT INTO malaria_parasite (receipt_id, malaria_parasite_pv, malaria_parasite_pf, reporting_datetime) VALUES ($receipt_id, '$malaria_parasite_pv', '$malaria_parasite_pf', NOW())";
        if (mysqli_query($conn, $insert_sql)) {
            $success = true;
            $edit_mode = true;
            $check_result = mysqli_query($conn, $check_sql);
            $existing_data = mysqli_fetch_assoc($check_result);
        } else {
            $error_message = "Error inserting record: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>MALARIA PARASITE Test Form</title>
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
        .success { 
		color: green; 
		}
        .error { 
		color: red; 
		}
		.message.success { 
		color: green; 
		}
    .message.error { 
	color: red; 
	}
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


    .reporting-time { text-align: center; margin-top: 15px; font-size: 14px; color: #555; }
</style>
</head>
<body>

<div class="container" style="margin-bottom: 100px;">
    <h2><?= $edit_mode ? "Edit Test Result" : "Enter Test Result" ?></h2>

    <?php if ($success): ?>
        <p class="message success">✅ Test result saved successfully!</p>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <p class="message error"><?= $error_message ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="malaria_parasite_pv">Blood Malaria Parasite PV Result:</label>
        <input type="text" name="malaria_parasite_pv" id="malaria_parasite_pv" required value="<?= htmlspecialchars($existing_data['malaria_parasite_pv']) ?>" />


        <label for="malaria_parasite_pf">Blood Malaria Parasite PF Result:</label>
        <input type="text" name="malaria_parasite_pf" id="malaria_parasite_pf" required value="<?= htmlspecialchars($existing_data['malaria_parasite_pf']) ?>" />

        <button type="submit"><?= $edit_mode ? "Update Result" : "Submit Result" ?></button>
    </form>

    <?php if (!empty($existing_data['reporting_datetime'])): ?>
        <p class="reporting-time"><strong>Last Reported:</strong> <?= $existing_data['reporting_datetime'] ?></p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
