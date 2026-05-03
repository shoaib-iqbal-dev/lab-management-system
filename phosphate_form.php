<?php
date_default_timezone_set("Asia/Karachi");
$conn = mysqli_connect("localhost", "root", "", "lab_system");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$success = false;
$error_message = "";
$is_edit = false;
$phosphate = "";

$receipt_id = isset($_GET['receipt_id']) ? intval($_GET['receipt_id']) : 0;

if ($receipt_id <= 0) {
    $error_message = "❌ Invalid or missing receipt ID in the URL.";
} else {
    $check = mysqli_query($conn, "SELECT * FROM phosphate WHERE receipt_id = $receipt_id");
    if ($row = mysqli_fetch_assoc($check)) {
        $is_edit = true;
        $phosphate = $row['phosphate'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $phosphate = $_POST['phosphate'];

        if ($is_edit) {
            $sql = "UPDATE phosphate SET phosphate = '$phosphate' WHERE receipt_id = $receipt_id";
        } else {
            $sql = "INSERT INTO phosphate (receipt_id, phosphate) VALUES ('$receipt_id', '$phosphate')";
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
    <title>Phosphate Test</title>
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
        input[type="text"] {
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
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .success { color: green; text-align: center; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>

<div class="form-container" style="margin-bottom: 100px;">
    <h2>Phosphate Test</h2>

    <?php if ($success): ?>
        <div class="message success">✅ Test result saved successfully!</div>
    <?php elseif (!empty($error_message)): ?>
        <div class="message error"><?= $error_message ?></div>
    <?php endif; ?>

    <?php if ($receipt_id > 0): ?>
    <form method="post">
        <label for="phosphate">Phosphate:</label>
        <input type="text" name="phosphate" value="<?= htmlspecialchars($phosphate) ?>" required>

        <button type="submit"><?= $is_edit ? 'Update Result' : 'Save Result' ?></button>
    </form>
    <?php endif; ?>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
