<?php
date_default_timezone_set("Asia/Karachi");

$conn = mysqli_connect("localhost", "root", "", "lab_system");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$success = false;
$error_message = "";
$is_edit = false;
$ck = $ldh = $ast = "";

$receipt_id = isset($_GET['receipt_id']) ? intval($_GET['receipt_id']) : 0;

if ($receipt_id <= 0) {
    $error_message = "❌ Invalid or missing receipt ID in the URL.";
} else {
    $check = mysqli_query($conn, "SELECT * FROM cardiac_enzyme WHERE receipt_id = $receipt_id");
    if ($row = mysqli_fetch_assoc($check)) {
        $is_edit = true;
        $ck = $row['ck'];
        $ldh = $row['ldh'];
        $ast = $row['ast'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ck = $_POST['ck'];
        $ldh = $_POST['ldh'];
        $ast = $_POST['ast'];

        if ($is_edit) {
            $sql = "UPDATE cardiac_enzyme SET ck = '$ck', ldh = '$ldh', ast = '$ast' WHERE receipt_id = $receipt_id";
        } else {
            $sql = "INSERT INTO cardiac_enzyme (receipt_id, ck, ldh, ast) VALUES ('$receipt_id', '$ck', '$ldh', '$ast')";
        }

        if (mysqli_query($conn, $sql)) {
            $success = true;
        } else {
            $error_message = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cardiac Enzyme Test Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 { text-align: center; margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-top: 10px; }
        input[type="number"] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover { background-color: #218838; }
        .message { text-align: center; font-weight: bold; margin-top: 20px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="form-container" style="margin-bottom: 100px;">
    <h2>Cardiac Enzyme Test</h2>

    <?php if ($success): ?>
        <div class="message success">✅ Test result saved successfully!</div>
    <?php elseif (!empty($error_message)): ?>
        <div class="message error"><?= $error_message ?></div>
    <?php endif; ?>

    <?php if ($receipt_id > 0): ?>
    <form method="post">
        <label for="ck">Creatine Kinase (CK):</label>
        <input type="number" name="ck" step="0.01" value="<?= htmlspecialchars($ck) ?>" required>

        <label for="ldh">LDH:</label>
        <input type="number" name="ldh" step="0.01" value="<?= htmlspecialchars($ldh) ?>" required>

        <label for="ast">AST:</label>
        <input type="number" name="ast" step="0.01" value="<?= htmlspecialchars($ast) ?>" required>

        <button type="submit"><?= $is_edit ? 'Update Result' : 'Save Result' ?></button>
    </form>
    <?php endif; ?>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
