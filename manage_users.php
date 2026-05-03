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

// Handle Delete
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $delete_id");
    header("Location: manage_users.php");
    exit();
}

// Handle Add User
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email']; // Get the email from the form
    $password = $_POST['password']; // Plain text password
    $role = $_POST['role'];
    mysqli_query($conn, "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')");
    header("Location: manage_users.php");
    exit();
}

// Handle Edit User
if (isset($_POST['edit_user'])) {
    $id = intval($_POST['edit_id']);
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($password)) {
        mysqli_query($conn, "UPDATE users SET username='$username', password='$password', role='$role' WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE users SET username='$username', role='$role' WHERE id=$id");
    }
    header("Location: manage_users.php");
    exit();
}

// Fetch All Users
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users - Admin Panel</title>
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
            margin: 20px auto; /* Center the table horizontally */
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

        th, td {
            padding: 10px;
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
</head>
<body>

<div class="table-container" style="margin-top: 45px;">

<div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back to Dashboard</a>
</div>	

    <h2 style="color:black">Manage Users</h2>

    <!-- Add User Form -->
    <div class="form-section">
        <form method="POST">
            <h3 style="margin-top: 0px;">Add New User</h3>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="add_user">Add User</button>
        </form>
    </div>

    <!-- Users Table -->
    <table>
        <thead>
            <tr>
                <th style="width:7%;">Serial No.</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $serial = 1; // Initialize serial number
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td style="text-align:center;"><?= $serial++ ?></td> <!-- Show serial number -->
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['role'] ?></td>
                <td class="action-links">
                    <a href="?edit=<?= $row['id'] ?>">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <!-- Edit Form -->
    <?php
    if (isset($_GET['edit'])) {
        $edit_id = intval($_GET['edit']);
        $edit_result = mysqli_query($conn, "SELECT * FROM users WHERE id = $edit_id");
        if (mysqli_num_rows($edit_result) > 0) {
            $edit_row = mysqli_fetch_assoc($edit_result);
    ?>
    <div class="form-section">
        <form method="POST">
            <h3>Edit User (ID: <?= $edit_row['id'] ?>)</h3>
            <input type="hidden" name="edit_id" value="<?= $edit_row['id'] ?>">
            <input type="text" name="username" value="<?= htmlspecialchars($edit_row['username']) ?>" required>
            <input type="text" name="password" placeholder="New Password (optional)">
            <select name="role" required>
                <option value="user" <?= $edit_row['role'] == 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $edit_row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
            <button type="submit" name="edit_user">Update</button>
        </form>
    </div>
    <?php } } ?>
</div>

</body>
</html>
<?php include "footer.php"; ?>
