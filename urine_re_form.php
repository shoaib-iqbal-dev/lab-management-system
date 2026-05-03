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
    $check_receipt_sql = "SELECT * FROM urine_re WHERE receipt_id = '$receipt_id'";
    $check_result = mysqli_query($conn, $check_receipt_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // A record exists, fetch the data to display it for editing
        $edit_mode = true;
        $existing_data = mysqli_fetch_assoc($check_result);
    } else {
        // No record found, the user is entering a new record
        $existing_data = [
            'colour' => '',
            'turbidity' => '',
			'quantity' => '',
            'appearance' => '',
			'sp_gravity' => '',
            'ph' => '',
            'leukocyte' => '',
            'nitrite' => '',
            'protein' => '',
            'sugar' => '',
            'ketones' => '',
            'urobilinogen' => '',
            'bilirubin' => '',
            'heamoglobin' => '',
            'pus_cells' => '',
			'rbc' => '',
			'epithelial' => '',
			'amorphous' => '',
			'calcium_oxalate' => '',
			'yeast_cells' => '',
			'dead_sperms' => '',
			'misc' => '',
			'granular_cast' => '',
			'hyaline_cast' => '',
			'tyrosine_crystal' => ''
        ];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $colour = $_POST['colour'];
    $turbidity = $_POST['turbidity'];
	$quantity = $_POST['quantity'];
	$appearance = $_POST['appearance'];
    $sp_gravity = $_POST['sp_gravity'];
    $ph = $_POST['ph'];
    $leukocyte = $_POST['leukocyte'];
    $nitrite = $_POST['nitrite'];
    $protein = $_POST['protein'];
    $sugar = $_POST['sugar'];
    $ketones = $_POST['ketones'];
    $urobilinogen = $_POST['urobilinogen'];
    $bilirubin = $_POST['bilirubin'];
    $heamoglobin = $_POST['heamoglobin'];
	$pus_cells = $_POST['pus_cells'];
	$rbc = $_POST['rbc'];
	$epithelial = $_POST['epithelial'];
	$amorphous = $_POST['amorphous'];
	$calcium_oxalate = $_POST['calcium_oxalate'];
	$yeast_cells = $_POST['yeast_cells'];
	$dead_sperms = $_POST['dead_sperms'];
	$misc = $_POST['misc'];
	$granular_cast = $_POST['granular_cast'];
	$hyaline_cast = $_POST['hyaline_cast'];
	$tyrosine_crystal = $_POST['tyrosine_crystal'];

    if ($edit_mode) {
        // Update existing record
        $update_sql = "UPDATE urine_re SET
                        colour = '$colour',
                        turbidity = '$turbidity',
						quantity = '$quantity',
						appearance = '$appearance',
                        sp_gravity = '$sp_gravity',
                        ph = '$ph',
                        leukocyte = '$leukocyte',
                        nitrite = '$nitrite',
                        protein = '$protein',
                        sugar = '$sugar',
                        ketones = '$ketones',
                        urobilinogen = '$urobilinogen',
                        bilirubin = '$bilirubin',
                        heamoglobin = '$heamoglobin',
                        pus_cells = '$pus_cells',
						rbc = '$rbc',
						epithelial = '$epithelial',
						amorphous = '$amorphous',
						calcium_oxalate = '$calcium_oxalate',
						yeast_cells = '$yeast_cells',
						dead_sperms = '$dead_sperms',
						misc = '$misc',
						granular_cast = '$granular_cast',
						hyaline_cast = '$hyaline_cast',
						tyrosine_crystal = '$tyrosine_crystal',
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
			$insert_sql = "INSERT INTO urine_re (
				receipt_id, colour, turbidity, quantity, appearance, sp_gravity, ph, leukocyte, nitrite, protein, sugar, ketones,
				urobilinogen, bilirubin, heamoglobin, pus_cells, rbc, epithelial, amorphous, calcium_oxalate,
				yeast_cells, dead_sperms, misc, granular_cast, hyaline_cast, tyrosine_crystal, reporting_datetime
			) VALUES (
				'$receipt_id', '$colour', '$turbidity', '$quantity', '$appearance', '$sp_gravity', '$ph', '$leukocyte', '$nitrite', '$protein', '$sugar', '$ketones',
				'$urobilinogen', '$bilirubin', '$heamoglobin', '$pus_cells', '$rbc', '$epithelial', '$amorphous', '$calcium_oxalate',
				'$yeast_cells', '$dead_sperms', '$misc', '$granular_cast', '$hyaline_cast', '$tyrosine_crystal', NOW()
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
    <title>Urine RE Test Result</title>
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
    <h2><?= $edit_mode ? "Edit URINRE RE Test Result" : "Enter URINRE RE Test Result" ?></h2>

    <?php if ($success): ?>
        <p class="message success">✅ Test result submitted successfully!</p>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <p class="message error"><?= $error_message ?></p>
    <?php endif; ?>

<form method="post">
    <div>
        <label for="colour">Colour:</label>
        <input type="text" name="colour" value="<?= $existing_data['colour'] ?>" required>
    </div>
    <div>
        <label for="turbidity">Turbidity:</label>
        <input type="text" name="turbidity" value="<?= $existing_data['turbidity'] ?>" required>
    </div>
	<div>
        <label for="quantity">Quantity:</label>
        <input type="text" name="quantity" value="<?= $existing_data['quantity'] ?>" required>
    </div>
    <div>
        <label for="appearance">Appearance:</label>
        <input type="text" name="appearance" value="<?= $existing_data['appearance'] ?>" required>
    </div>
	<div>
        <label for="appearance">SP Gravity:</label>
        <input type="text" name="sp_gravity" value="<?= $existing_data['sp_gravity'] ?>" required>
    </div>
    <div>
        <label for="ph">pH:</label>
        <input type="text" " name="ph" value="<?= $existing_data['ph'] ?>" required>
    </div>
    <div>
        <label for="leukocyte">Leukocyte:</label>
        <input type="text" name="leukocyte" value="<?= $existing_data['leukocyte'] ?>" required>
    </div>
    <div>
        <label for="nitrite">Nitrite:</label>
        <input type="text" name="nitrite" value="<?= $existing_data['nitrite'] ?>" required>
    </div>
    <div>
        <label for="protein">Protein:</label>
        <input type="text" name="protein" value="<?= $existing_data['protein'] ?>" required>
    </div>
    <div>
        <label for="sugar">Sugar:</label>
        <input type="text" name="sugar" value="<?= $existing_data['sugar'] ?>" required>
    </div>
    <div>
        <label for="ketones">Ketones:</label>
        <input type="text" name="ketones" value="<?= $existing_data['ketones'] ?>" required>
    </div>
    <div>
        <label for="urobilinogen">Urobilinogen:</label>
        <input type="text" name="urobilinogen" value="<?= $existing_data['urobilinogen'] ?>" required>
    </div>
    <div>
        <label for="bilirubin">Bilirubin:</label>
        <input type="text" name="bilirubin" value="<?= $existing_data['bilirubin'] ?>" required>
    </div>
    <div>
        <label for="heamoglobin">Haemoglobin:</label>
        <input type="text" name="heamoglobin" value="<?= $existing_data['heamoglobin'] ?>" required>
    </div>
    <div>
        <label for="pus_cells">Pus Cells / WBC:</label>
        <input type="text" name="pus_cells" value="<?= $existing_data['pus_cells'] ?>" required>
    </div>
    <div>
        <label for="rbc">Red Blood Cell:</label>
        <input type="text" name="rbc" value="<?= $existing_data['rbc'] ?>" required>
    </div>
    <div>
        <label for="epithelial">Epithelial:</label>
        <input type="text" name="epithelial" value="<?= $existing_data['epithelial'] ?>" required>
    </div>
    <div>
        <label for="amorphous">Amorphous:</label>
        <input type="text" name="amorphous" value="<?= $existing_data['amorphous'] ?>" required>
    </div>
    <div>
        <label for="calcium_oxalate">Calcium Oxalate:</label>
        <input type="text" name="calcium_oxalate" value="<?= $existing_data['calcium_oxalate'] ?>" required>
    </div>
    <div>
        <label for="yeast_cells">Yeast Cells:</label>
        <input type="text" name="yeast_cells" value="<?= $existing_data['yeast_cells'] ?>" required>
    </div>
    <div>
        <label for="dead_sperms">Dead Sperms:</label>
        <input type="text" name="dead_sperms" value="<?= $existing_data['dead_sperms'] ?>" required>
    </div>
    <div>
        <label for="misc">Miscellaneous:</label>
        <input type="text" name="misc" value="<?= $existing_data['misc'] ?>" required>
    </div>
    <div>
        <label for="tyrosine_crystal">Tyrosine Crystals:</label>
        <input type="text" name="tyrosine_crystal" value="<?= $existing_data['tyrosine_crystal'] ?>" required>
    </div>
	    <div>
        <label for="granular_cast"> Granular Cast:</label>
        <input type="text" name="granular_cast" value="<?= $existing_data['granular_cast'] ?>" required>
    </div>
	    <div>
        <label for="hyaline_cast">Hyaline Cast:</label>
        <input type="text" name="hyaline_cast" value="<?= $existing_data['hyaline_cast'] ?>" required>
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
