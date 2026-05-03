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
    $check_receipt_sql = "SELECT * FROM semen_analysis WHERE receipt_id = '$receipt_id'";
    $check_result = mysqli_query($conn, $check_receipt_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // A record exists, fetch the data to display it for editing
        $edit_mode = true;
        $existing_data = mysqli_fetch_assoc($check_result);
    } else {
        // No record found, the user is entering a new record
        $existing_data = [
            'colour' => '',
            'quantity' => '',
            'consistancy' => '',
            'ph' => '',
            'liquification_time' => '',
            'total_sperm_count' => '',
            'active_motile' => '',
            'motile' => '',
            'non_motile' => '',
            'wbc_cells' => '',
        ];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $colour = $_POST['colour'];
    $quantity = $_POST['quantity'];
    $consistancy = $_POST['consistancy'];
    $ph = $_POST['ph'];
    $liquification_time = $_POST['liquification_time'];
    $total_sperm_count = $_POST['total_sperm_count'];
    $active_motile = $_POST['active_motile'];
    $motile = $_POST['motile'];
    $non_motile = $_POST['non_motile'];
    $wbc_cells = $_POST['wbc_cells'];

    if ($edit_mode) {
        // Update existing record
        $update_sql = "UPDATE semen_analysis SET
                        colour = '$colour',
                        quantity = '$quantity',
                        consistancy = '$consistancy',
                        ph = '$ph',
                        liquification_time = '$liquification_time',
                        total_sperm_count = '$total_sperm_count',
                        active_motile = '$active_motile',
                        motile = '$motile',
                        non_motile = '$non_motile',
                        wbc_cells = '$wbc_cells',
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
        $insert_sql = "INSERT INTO semen_analysis (
                        receipt_id, colour, quantity, consistancy, ph, liquification_time, total_sperm_count, active_motile, motile,
                        non_motile, wbc_cells, reporting_datetime
                    ) VALUES (
                        '$receipt_id', '$colour', '$quantity', '$consistancy', '$ph', '$liquification_time', '$total_sperm_count', '$active_motile',
                        '$motile', '$non_motile', '$wbc_cells', NOW()
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
    <title>SEMEN ANALYSIS Test Result</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }
		
		.header h2 {
    color: white; /* Make h1 text white */
}

        .form-container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 30px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #444;
        }

        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            transition: border 0.3s ease;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        button[type="submit"] {
            grid-column: span 2;
            background-color: #007bff;
            color: #fff;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .message.success { color: green; }
        .message.error { color: red; }
		.reporting-info { margin-top: 20px; font-size: 14px; color: #333; text-align: center; }

    </style>
</head>
<body>

<div class="form-container" style="margin-bottom: 100px;">
    <h2><?= $edit_mode ? "Edit Test Result" : "Enter Test Result" ?></h2>

    <?php if ($success): ?>
        <p class="message success">✅ Test result submitted successfully!</p>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <p class="message error"><?= $error_message ?></p>
    <?php endif; ?>

    <form method="post">
        <div>
            <label for="colour">Colour:</label>
            <input type="text" step="0.01" name="colour" value="<?= $existing_data['colour'] ?>" required>
        </div>
        <div>
            <label for="quantity">Quantity:</label>
            <input type="text" step="0.01" name="quantity" value="<?= $existing_data['quantity'] ?>" required>
        </div>
        <div>
            <label for="consistancy">Consistancy:</label>
            <input type="text" step="0.01" name="consistancy" value="<?= $existing_data['consistancy'] ?>" required>
        </div>
        <div>
            <label for="ph">PH:</label>
            <input type="text" step="0.01" name="ph" value="<?= $existing_data['ph'] ?>" required>
        </div>
        <div>
            <label for="liquification_time">Liquification Time:</label>
            <input type="text" step="0.01" name="liquification_time" value="<?= $existing_data['liquification_time'] ?>" required>
        </div>
        <div>
            <label for="total_sperm_count">Total Sperm Count:</label>
            <input type="text" step="0.01" name="total_sperm_count" value="<?= $existing_data['total_sperm_count'] ?>" required>
        </div>
        <div>
            <label for="active_motile">Active Motile:</label>
            <input type="text" step="0.01" name="active_motile" value="<?= $existing_data['active_motile'] ?>" required>
        </div>
        <div>
            <label for="motile">Motile:</label>
            <input type="text" step="0.01" name="motile" value="<?= $existing_data['motile'] ?>" required>
        </div>
        <div>
            <label for="non_motile">Non Motile:</label>
            <input type="text" step="0.01" name="non_motile" value="<?= $existing_data['non_motile'] ?>" required>
        </div>
        <div>
            <label for="wbc_cells">Wbc Cells:</label>
            <input type="text" step="0.01" name="wbc_cells" value="<?= $existing_data['wbc_cells'] ?>" required>
        </div>
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

<?php
// Close the database connection
mysqli_close($conn);
?>
