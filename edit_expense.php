<?php
session_start();
include "db.php";
include "header.php";
include "auth_check.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    die("❌ Expense ID is missing.");
}

$expense_id = intval($_GET['id']);
$message = "";

// Handle form submission for update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expense_name = mysqli_real_escape_string($conn, $_POST['expense_name']);
    $amount = (float) $_POST['amount'];
    $expense_date = $_POST['expense_date'];

    $update = "UPDATE lab_expenses 
               SET expense_name = '$expense_name', amount = '$amount', expense_date = '$expense_date' 
               WHERE id = $expense_id";

    if (mysqli_query($conn, $update)) {
        $message = "✅ Expense updated successfully!";
    } else {
        $message = "❌ Failed to update expense: " . mysqli_error($conn);
    }
}

// Fetch current expense data
$expense = mysqli_query($conn, "SELECT * FROM lab_expenses WHERE id = $expense_id LIMIT 1");
if (mysqli_num_rows($expense) == 0) {
    die("❌ Expense not found.");
}
$data = mysqli_fetch_assoc($expense);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Expense - Lab Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        form {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }

        form input, form button {
            padding: 10px;
            width: 100%;
            margin-bottom: 15px;
        }

        .message {
            text-align: center;
            color: green;
            font-weight: bold;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Edit Expense</h2>

<?php if ($message): ?>
    <p class="message"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="expense_name" value="<?php echo htmlspecialchars($data['expense_name']); ?>" required>
    <input type="number" name="amount" value="<?php echo $data['amount']; ?>" step="0.01" required>
    <input type="date" name="expense_date" value="<?php echo $data['expense_date']; ?>" required>
    <button type="submit">💾 Update Expense</button>
</form>

<div class="back-link">
    <a href="add_expense.php">← Back to Expense List</a>
</div>

</body>
</html>

<?php include "footer.php"; ?>
