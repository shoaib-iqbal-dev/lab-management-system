<?php
session_start();
include "db.php";
include "header.php";
include "auth_check.php";

date_default_timezone_set("Asia/Karachi");

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM doctors WHERE id = $id");
    header("Location: doctor_commission.php?page=$page" . (isset($_GET['search']) ? "&search=" . urlencode($_GET['search']) : ""));
    exit();
}

// Handle edit
$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$edit_data = null;

if ($edit_id > 0) {
    $res = mysqli_query($conn, "SELECT * FROM doctors WHERE id = $edit_id LIMIT 1");
    if (mysqli_num_rows($res) > 0) {
        $edit_data = mysqli_fetch_assoc($res);
    }
}

// Handle form submit (add or update)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_name = mysqli_real_escape_string($conn, $_POST['doctor_name']);
    $commission = floatval($_POST['commission']);
    $edit_id_post = intval($_POST['edit_id']);

    // Check for duplicate (case insensitive)
    $name_check_sql = "SELECT id FROM doctors WHERE LOWER(name) = LOWER('$doctor_name')";
    if ($edit_id_post) {
        $name_check_sql .= " AND id != $edit_id_post";
    }
    $name_check = mysqli_query($conn, $name_check_sql);

    if (mysqli_num_rows($name_check) > 0) {
        $error = "Doctor with this name already exists.";
    } elseif (!empty($doctor_name) && $commission > 0 && $commission <= 100) {
        if ($edit_id_post > 0) {
            // Update
            $query = "UPDATE doctors SET name='$doctor_name', commission_percentage='$commission' WHERE id=$edit_id_post";
            $success = "Doctor updated successfully!";
        } else {
            // Insert
            $query = "INSERT INTO doctors (name, commission_percentage) VALUES ('$doctor_name', '$commission')";
            $success = "Doctor added successfully!";
        }
        mysqli_query($conn, $query);
        // Redirect to avoid form resubmission and reset form data
        $redirect_url = "doctor_commission.php?page=$page";
        if (!empty($_GET['search'])) {
            $redirect_url .= "&search=" . urlencode($_GET['search']);
        }
        header("Location: $redirect_url");
        exit();
    } else {
        $error = "Please enter a valid doctor name and commission (1-100%).";
    }
}

// Get search parameter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Count total records based on search
if (!empty($search)) {
    $total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM doctors WHERE name LIKE '%$search%'");
} else {
    $total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM doctors");
}
$total_row = mysqli_fetch_assoc($total_result);
$total_doctors = $total_row['total'];
$total_pages = ceil($total_doctors / $limit);

// Fetch doctors with pagination and optional search
if (!empty($search)) {
    $result = mysqli_query($conn, "SELECT * FROM doctors WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $limit OFFSET $offset");
} else {
    $result = mysqli_query($conn, "SELECT * FROM doctors ORDER BY id DESC LIMIT $limit OFFSET $offset");
}

$search_param = !empty($search) ? '&search=' . urlencode($search) : '';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Commission Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
        }

        .table-container {
            width: 95%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2, h3 {
            text-align: center;
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
            background: #218838;
        }
        
        .form-section {
            margin-top: 20px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: center;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        .action-buttons a {
            margin: 0 4px;
            text-decoration: none;
            color: white;
            padding: 5px 8px;
            border-radius: 4px;
        }

        .edit-btn {
            background: #007bff;
        }

        .delete-btn {
            background: #dc3545;
        }

        .message {
            text-align: center;
            font-weight: bold;
        }
        
        
         header {
            background-color: #3498db;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-link {
            text-decoration: none;
            color: inherit;
        }

        .logout-btn {
            color: white;
            background: #e74c3c;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 12px;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 40px;
        }

        .message.success { color: green; }
        .message.error { color: red; }
		
		
				
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

		
		
    </style>

    <script>
        function validateForm() {
            const commission = parseFloat(document.forms["docForm"]["commission"].value);
            if (commission <= 0 || commission > 100) {
                alert("Commission must be between 1 and 100%");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<main class="table-container">

<div style="text-align: center; margin-top: 20px;">
    <a href="doctor_commissions_list.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back</a>
</div>

    <h2><?= $edit_data ? "Edit Doctor" : "Add New Doctor" ?></h2>

    <?php if (!empty($success)) echo "<p class='message success'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>

    <div class="form-section">
    <form method="POST" name="docForm" onsubmit="return validateForm()">
        <input type="hidden" name="edit_id" value="<?= $edit_data ? $edit_data['id'] : 0 ?>">
        <label>Doctor Name:</label>
        <input type="text" name="doctor_name" required value="<?= $edit_data ? htmlspecialchars($edit_data['name']) : '' ?>">

        <label>Commission Percentage (%):</label>
        <input type="number" step="0.01" name="commission" required value="<?= $edit_data ? $edit_data['commission_percentage'] : '' ?>">

        <button type="submit"><?= $edit_data ? "Update Doctor" : "Add Doctor" ?></button>
    </form>
    </div>

<form method="GET" style="text-align:center; margin-bottom: 20px;">
    <input type="text" name="search" placeholder="Search doctor name..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" style="padding: 6px; width: 60%;">
    <button type="submit" style="padding: 6px 12px;">Search</button>
</form>

    <h3>Existing Doctors</h3>
    <table>
        <tr>
            <th>S.No</th>
            <th>Doctor Name</th>
            <th>Commission (%)</th>
            <th>Actions</th>
        </tr>
        <?php 
        // Calculate serial number offset based on page and limit
        $serial_no = $offset + 1;
        while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $serial_no++; ?></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= $row['commission_percentage']; ?>%</td>
            <td class="action-buttons">
                <a href="?edit=<?= $row['id'] . '&page=' . $page . $search_param ?>" class="edit-btn">Edit</a>
                <a href="?delete=<?= $row['id'] . '&page=' . $page . $search_param ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    
    <!-- Pagination links -->
    <div style="text-align:center; margin-top: 20px;">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 . $search_param ?>" style="margin-right:10px;">&laquo; Prev</a>
        <?php endif; ?>
        Page <?= $page ?> of <?= $total_pages ?>
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 . $search_param ?>" style="margin-left:10px;">Next &raquo;</a>
        <?php endif; ?>
    </div>

</main>

<?php include "footer.php"; ?>
</
