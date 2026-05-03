<?php
date_default_timezone_set("Asia/Karachi");
$conn = mysqli_connect("localhost", "root", "", "lab_system");


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$success = false;
$error_message = "";
$edit_mode = false;

// Retrieve receipt_id from the URL
$receipt_id = isset($_GET['receipt_id']) ? intval($_GET['receipt_id']) : 0;

if ($receipt_id <= 0) {
    $error_message = "❌ Invalid or missing receipt ID.";
} else {
    // Check if a record exists for the given receipt_id
    $check_receipt_sql = "SELECT * FROM serum_monomeric_prolactin WHERE receipt_id = '$receipt_id'";
    $check_result = mysqli_query($conn, $check_receipt_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // A record exists, fetch the data to display it for editing
        $edit_mode = true;
        $existing_data = mysqli_fetch_assoc($check_result);
    } else {
        // No record found, the user is entering a new record
        $existing_data = [
            'serum_monomeric_prolactin' => '',
        ];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $serum_monomeric_prolactin = $_POST['serum_monomeric_prolactin'];
    

    if ($edit_mode) {
        // Update existing record
        $update_sql = "UPDATE serum_monomeric_prolactin SET
                        serum_monomeric_prolactin = '$serum_monomeric_prolactin',
                        reporting_datetime = NOW()
                    WHERE receipt_id = '$receipt_id'";

               if (mysqli_query($conn, $update_sql)) {
            $success = true;

            // 🔄 Refresh the data after update
            $check_result = mysqli_query($conn, $check_receipt_sql);
            $existing_data = mysqli_fetch_assoc($check_result);
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    } else {
        // Insert new record
        $insert_sql = "INSERT INTO serum_monomeric_prolactin (
                        receipt_id, serum_monomeric_prolactin, reporting_datetime
                    ) VALUES (
                        '$receipt_id', '$serum_monomeric_prolactin', NOW()
                    )";

        if (mysqli_query($conn, $insert_sql)) {
            $success = true;
            $edit_mode = true;

            // 🔄 Fetch data just inserted
            $check_result = mysqli_query($conn, $check_receipt_sql);
            $existing_data = mysqli_fetch_assoc($check_result);
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
    <title>Serum Monomeric Prolactin Test Result</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <h2><?= $edit_mode ? "Edit Serum Monomeric Prolactin Test Result" : "Enter Serum Monomeric Prolactin Test Result" ?></h2>

    <?php if ($success): ?>
        <p class="message success">✅ Test result submitted successfully!</p>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <p class="message error"><?= $error_message ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="serum_monomeric_prolactin">Serum Monomeric Prolactin:</label>
        <input type="text" name="serum_monomeric_prolactin" id="serum_monomeric_prolactin" required value="<?= htmlspecialchars($existing_data['serum_monomeric_prolactin']) ?>" />

        <button type="submit"><?= $edit_mode ? "Update Result" : "Submit Result" ?></button>
    </form>
	
    <?php if (!empty($existing_data['reporting_datetime'])): ?>
        <p class="reporting-time"><strong>Last Reported:</strong> <?= $existing_data['reporting_datetime'] ?></p>
    <?php endif; ?>

	
	
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
