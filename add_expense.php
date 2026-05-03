<?php
session_start();
include "db.php";
include "header.php";
include "auth_check.php";

date_default_timezone_set("Asia/Karachi");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Add expense
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_expense'])) {
    $expense_name = mysqli_real_escape_string($conn, $_POST['expense_name']);
    $amount = (float) $_POST['amount'];
    $expense_date = $_POST['expense_date'];

    $query = "INSERT INTO lab_expenses (expense_name, amount, expense_date) VALUES ('$expense_name', $amount, '$expense_date')";
    mysqli_query($conn, $query);
}

// Date filter and search filter
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
$where = "WHERE 1=1";

if ($from && $to) {
    $where .= " AND expense_date BETWEEN '$from' AND '$to'";
}

if (!empty($search)) {
    $where .= " AND expense_name LIKE '%$search%'";
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM lab_expenses $where");
$total = mysqli_fetch_assoc($total_query)['total'];
$pages = ceil($total / $limit);

$expenses = mysqli_query($conn, "SELECT * FROM lab_expenses $where ORDER BY expense_date DESC LIMIT $start, $limit");

$total_amount_query = mysqli_query($conn, "SELECT SUM(amount) as total FROM lab_expenses $where");
$total_expense = mysqli_fetch_assoc($total_amount_query)['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Lab Expenses</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .container {
        width: 90%;
        margin: auto;
    }

    .section-title {
        text-align: center;
        margin: 20px 0;
    }

    .form-wrapper, .filter-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .form-box, .filter-box {
        background: #f9f9f9;
        padding: 15px 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-align: center;
    }

    .form-box input, .filter-box input {
        padding: 8px;
        margin: 5px;
        width: 200px;
    }

    .form-box button, .filter-box button {
        padding: 8px 16px;
        background: #3498db;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 3px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th {
        background: #3498db;
        color: white;
    }

    th, td {
        padding: 10px;
        text-align: center;
    }

    .pagination {
        text-align: center;
        margin-top: 15px;
    }

    .pagination a {
        display: inline-block;
        margin: 0 4px;
        padding: 6px 12px;
        background: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }

    .total-expense {
        text-align: center;
        font-weight: bold;
        padding: 10px 0;
    }

    .edit-link {
        color: #007bff;
        text-decoration: none;
        margin-right: 8px;
    }

    .delete-link {
        color: red;
        text-decoration: none;
    }
	
			
						.back-button {
			display: inline-block;
			background-color: #007BFF;  /* Bootstrap-like blue */
			color: white;
			padding: 10px 20px;
			border-radius: 5px;
			text-decoration: none;
			font-weight: bold;
			font-size: 16px;
			transition: background-color 0.3s ease;
			margin-bottom: 20px;
			cursor: pointer;
		}

		.back-button:hover {
			background-color: #0056b3;  /* Darker blue on hover */
			text-decoration: none;
			color: white;
		}

		        .container1 {
            background: #f9f9f9;
            padding: 30px;
            margin: 20px 50px;
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
        }
	
</style>

</head>
<body>

<div class="container1">
<div style="text-align: center; margin-top: 20px;">
    <a href="sales_report.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back</a>
</div>

    <h2 class="section-title">💸 Manage Lab Expenses</h2>



    <div class="form-box">
        <form method="POST">
            <input type="text" name="expense_name" placeholder="Expense Name" required>
            <input type="number" name="amount" placeholder="Amount" step="0.01" required>
            <input type="date" name="expense_date" value="<?= date('Y-m-d') ?>" required>
            <button type="submit" name="add_expense">Add Expense</button>
        </form>
    </div>

    <div class="filter-box">
        <form method="GET">
            <label><strong>From:</strong></label>
            <input type="date" name="from" value="<?= $from ?>">
            <label><strong>To:</strong></label>
            <input type="date" name="to" value="<?= $to ?>">
            <input type="text" name="search" placeholder="Search Expense" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Filter</button>
        </form>
    </div>

    <div class="total-expense">
        Total Expense: Rs <?= number_format($total_expense, 2) ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Serial No.</th>
                <th>Expense Name</th>
                <th>Amount (Rs)</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($expenses) > 0): ?>
            <?php 
                $serial = $start + 1; // Calculate serial number based on page and limit
                while ($row = mysqli_fetch_assoc($expenses)): 
            ?>
                <tr>
                    <td><?= $serial++ ?></td>
                    <td><?= htmlspecialchars($row['expense_name']) ?></td>
                    <td><?= number_format($row['amount'], 2) ?></td>
                    <td><?= $row['expense_date'] ?></td>
                    <td>
                        <a href="edit_expense.php?id=<?= $row['id'] ?>" class="edit-link">✏️ Edit</a>
                        <a href="delete_expense.php?id=<?= $row['id'] ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this expense?')">🗑️ Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No expenses found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a href="?from=<?= $from ?>&to=<?= $to ?>&search=<?= urlencode($search) ?>&page=<?= $i ?>" <?= $i === $page ? 'style="background:#2c80b4;"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>
    </div>



</div>

</body>
</html>

<?php include "footer.php"; ?>
