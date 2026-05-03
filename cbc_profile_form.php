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
    $check_receipt_sql = "SELECT * FROM cbc_profile WHERE receipt_id = '$receipt_id'";
    $check_result = mysqli_query($conn, $check_receipt_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // A record exists, fetch the data to display it for editing
        $edit_mode = true;
        $existing_data = mysqli_fetch_assoc($check_result);
    } else {
        // No record found, the user is entering a new record
        $existing_data = [
            'haemoglobin' => '',
            'rbc' => '',
            'haematocrit' => '',
            'mcv' => '',
            'mch' => '',
            'mchc' => '',
            'white_cells' => '',
            'neutrophils' => '',
            'lymphocytes' => '',
            'monocytes' => '',
            'eosinophils' => '',
            'basophils' => '',
            'platelet_count' => ''
        ];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $haemoglobin = $_POST['haemoglobin'];
    $rbc = $_POST['rbc'];
    $haematocrit = $_POST['haematocrit'];
    $mcv = $_POST['mcv'];
    $mch = $_POST['mch'];
    $mchc = $_POST['mchc'];
    $white_cells = $_POST['white_cells'];
    $neutrophils = $_POST['neutrophils'];
    $lymphocytes = $_POST['lymphocytes'];
    $monocytes = $_POST['monocytes'];
    $eosinophils = $_POST['eosinophils'];
    $basophils = $_POST['basophils'];
    $platelet_count = $_POST['platelet_count'];

    if ($edit_mode) {
        // Update existing record
        $update_sql = "UPDATE cbc_profile SET
                        haemoglobin = '$haemoglobin',
                        rbc = '$rbc',
                        haematocrit = '$haematocrit',
                        mcv = '$mcv',
                        mch = '$mch',
                        mchc = '$mchc',
                        white_cells = '$white_cells',
                        neutrophils = '$neutrophils',
                        lymphocytes = '$lymphocytes',
                        monocytes = '$monocytes',
                        eosinophils = '$eosinophils',
                        basophils = '$basophils',
                        platelet_count = '$platelet_count',
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
        $insert_sql = "INSERT INTO cbc_profile (
                        receipt_id, haemoglobin, rbc, haematocrit, mcv, mch, mchc, white_cells, neutrophils,
                        lymphocytes, monocytes, eosinophils, basophils, platelet_count, reporting_datetime
                    ) VALUES (
                        '$receipt_id', '$haemoglobin', '$rbc', '$haematocrit', '$mcv', '$mch', '$mchc', '$white_cells',
                        '$neutrophils', '$lymphocytes', '$monocytes', '$eosinophils', '$basophils', '$platelet_count', NOW()
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
    <title>CBC Profile Test Result</title>
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
            <label for="haemoglobin">Haemoglobin:</label>
            <input type="text" step="0.01" name="haemoglobin" value="<?= $existing_data['haemoglobin'] ?>" required>
        </div>
        <div>
            <label for="rbc">RBC:</label>
            <input type="text" step="0.01" name="rbc" value="<?= $existing_data['rbc'] ?>" required>
        </div>
        <div>
            <label for="haematocrit">Haematocrit:</label>
            <input type="text" step="0.01" name="haematocrit" value="<?= $existing_data['haematocrit'] ?>" required>
        </div>
        <div>
            <label for="mcv">MCV:</label>
            <input type="text" step="0.01" name="mcv" value="<?= $existing_data['mcv'] ?>" required>
        </div>
        <div>
            <label for="mch">MCH:</label>
            <input type="text" step="0.01" name="mch" value="<?= $existing_data['mch'] ?>" required>
        </div>
        <div>
            <label for="mchc">MCHC:</label>
            <input type="text" step="0.01" name="mchc" value="<?= $existing_data['mchc'] ?>" required>
        </div>
        <div>
            <label for="white_cells">White Cells:</label>
            <input type="text" step="0.01" name="white_cells" value="<?= $existing_data['white_cells'] ?>" required>
        </div>
        <div>
            <label for="neutrophils">Neutrophils:</label>
            <input type="text" step="0.01" name="neutrophils" value="<?= $existing_data['neutrophils'] ?>" required>
        </div>
        <div>
            <label for="lymphocytes">Lymphocytes:</label>
            <input type="text" step="0.01" name="lymphocytes" value="<?= $existing_data['lymphocytes'] ?>" required>
        </div>
        <div>
            <label for="monocytes">Monocytes:</label>
            <input type="text" step="0.01" name="monocytes" value="<?= $existing_data['monocytes'] ?>" required>
        </div>
        <div>
            <label for="eosinophils">Eosinophils:</label>
            <input type="text" step="0.01" name="eosinophils" value="<?= $existing_data['eosinophils'] ?>" required>
        </div>
        <div>
            <label for="basophils">Basophils:</label>
            <input type="text" step="0.01" name="basophils" value="<?= $existing_data['basophils'] ?>" required>
        </div>
        <div>
            <label for="platelet_count">Platelet Count:</label>
            <input type="text" step="0.01" name="platelet_count" value="<?= $existing_data['platelet_count'] ?>" required>
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
