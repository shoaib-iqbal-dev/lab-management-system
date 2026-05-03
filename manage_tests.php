<?php
session_start();
include "db.php";
include "header.php";
include "auth_check.php";

// Redirect if not logged in or not admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle Delete Test
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM tests WHERE id = $delete_id");
    header("Location: manage_tests.php");
    exit();
}

// Handle Add Test
if (isset($_POST['add_test'])) {
    $test_name = $_POST['test_name'];
    $fee = $_POST['fee'];

    // Check if test name already exists
    $check_query = mysqli_query($conn, "SELECT * FROM tests WHERE test_name = '$test_name'");
    if (mysqli_num_rows($check_query) > 0) {
        $error_message = "Test with this name already exists.";
    } else {
        mysqli_query($conn, "INSERT INTO tests (test_name, fee) VALUES ('$test_name', '$fee')");
        header("Location: manage_tests.php");
        exit();
    }
}

// Handle Edit Test (Now includes test name)
if (isset($_POST['edit_test'])) {
    $id = intval($_POST['edit_id']);
    $fee = $_POST['fee'];
    $test_name = $_POST['test_name'];

    mysqli_query($conn, "UPDATE tests SET test_name='$test_name', fee='$fee' WHERE id=$id");
    header("Location: manage_tests.php");
    exit();
}

// Handle Search
$search_query = "";
$search_term = "";
if (isset($_POST['search_term'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term']);
    $search_query = "WHERE test_name LIKE '%$search_term%'";
} elseif (isset($_GET['search_term'])) {
    $search_term = mysqli_real_escape_string($conn, $_GET['search_term']);
    $search_query = "WHERE test_name LIKE '%$search_term%'";
}

// Pagination Variables
$tests_per_page = 20;
$total_tests_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tests $search_query");
$total_tests_row = mysqli_fetch_assoc($total_tests_query);
$total_tests = $total_tests_row['total'];
$total_pages = ceil($total_tests / $tests_per_page);

$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($current_page > $total_pages) $current_page = $total_pages;
if ($current_page < 1) $current_page = 1;

$start_record = ($current_page - 1) * $tests_per_page;

// Fetch Tests
$result = mysqli_query($conn, "SELECT * FROM tests $search_query LIMIT $start_record, $tests_per_page");

// Edit Test
$edit_test = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $test_query = mysqli_query($conn, "SELECT * FROM tests WHERE id = $edit_id");
    $edit_test = mysqli_fetch_assoc($test_query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Tests and Fees - Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .table-container {
            width: 95%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th {
            font-weight: bold;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            background-color: #3498db;
            color: white;
        }

        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .action-links a {
            margin-right: 10px;
            text-decoration: none;
            color: blue;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        form input, form select {
            padding: 7px;
            margin: 5px;
        }

        form button {
            padding: 7px 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #218838;
        }

        .form-section {
            margin-top: 20px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: center;
        }

        h2 {
            text-align: center;
        }

        h3 {
            text-align: center;
            color: #333;
        }

        .cancel-btn {
            display: inline-block;
            padding: 7px 15px;
            margin-left: 10px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .cancel-btn:hover {
            background-color: #c82333;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination .active {
            background-color: #004085;
        }

        .back-button {
            display: inline-block;
            background-color: #007BFF;
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
            background-color: #0056b3;
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>

<div class="table-container">

<div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back to Dashboard</a>
</div>	

    <h2>Manage Tests and Fees</h2>

    <!-- Add Test Form -->
    <div class="form-section">
        <form method="POST">
            <h3>Add New Test</h3>
            <input type="text" name="test_name" placeholder="Test Name" required>
            <input type="number" name="fee" placeholder="Fee" required step="0.01">
            <button type="submit" name="add_test">Add Test</button>
        </form>
        <?php if (isset($error_message)) { ?>
            <p style="color: red;"><?= $error_message; ?></p>
        <?php } ?>
    </div>

    <!-- Search Form -->
    <form method="POST" style="text-align: center;">
        <input type="text" name="search_term" placeholder="Search Test Name" value="<?= htmlspecialchars($search_term); ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Edit Test Form -->
    <?php if ($edit_test) { ?>
        <div class="form-section">
            <form method="POST">
                <h3>Edit Test - <?= htmlspecialchars($edit_test['test_name']); ?></h3>
                <input type="text" name="test_name" value="<?= htmlspecialchars($edit_test['test_name']); ?>" required>
                <input type="number" name="fee" value="<?= $edit_test['fee']; ?>" step="0.01" required>
                <input type="hidden" name="edit_id" value="<?= $edit_test['id']; ?>">
                <button type="submit" name="edit_test">Update</button>
                <a href="manage_tests.php" class="cancel-btn">Cancel</a>
            </form>
        </div>
    <?php } ?>

    <!-- Tests Table -->
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Test Name</th>
                <th>Fee</th>
                <th>Actions</th>
            </tr>
            </tr>
        </thead>
        <tbody>
        <?php
        $serial = 1;
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $start_record + $serial++ ?></td>
                <td><?= htmlspecialchars($row['test_name']) ?></td>
                <td><?= number_format($row['fee'], 2) ?></td>
                <td class="action-links">
                    <a href="?edit=<?= $row['id'] ?>&search_term=<?= urlencode($search_term) ?>&page=<?= $current_page ?>">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>&search_term=<?= urlencode($search_term) ?>&page=<?= $current_page ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php
        $search_param = '&search_term=' . urlencode($search_term);

        if ($current_page > 1) {
            echo '<a href="manage_tests.php?page=' . ($current_page - 1) . $search_param . '">Previous</a>';
        }

        for ($page = 1; $page <= $total_pages; $page++) {
            $active_class = ($page == $current_page) ? 'active' : '';
            echo '<a href="manage_tests.php?page=' . $page . $search_param . '" class="' . $active_class . '">' . $page . '</a>';
        }

        if ($current_page < $total_pages) {
            echo '<a href="manage_tests.php?page=' . ($current_page + 1) . $search_param . '">Next</a>';
        }
        ?>
    </div>
</div>

</body>
</html>

<?php include "footer.php"; ?>
