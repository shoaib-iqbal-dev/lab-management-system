<?php
include "db.php";
include "header.php";
include "auth_check.php";

date_default_timezone_set("Asia/Karachi");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Pagination settings
$limit = 10; // patients per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$search = '';
$patients = [];
$total_patients = 0;

// If user searched
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);

    // Search patients
    $query = "SELECT * FROM patients WHERE mr_no LIKE '%$search%' OR name LIKE '%$search%' ORDER BY id DESC LIMIT $limit OFFSET $offset";
    $count_query = "SELECT COUNT(*) AS total FROM patients WHERE mr_no LIKE '%$search%' OR name LIKE '%$search%'";
} else {
    // Show all patients
    $query = "SELECT * FROM patients ORDER BY id DESC LIMIT $limit OFFSET $offset";
    $count_query = "SELECT COUNT(*) AS total FROM patients";
}

// Fetch patients
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $patients[] = $row;
}

// Fetch total patients count for pagination
$count_result = mysqli_query($conn, $count_query);
if ($count_row = mysqli_fetch_assoc($count_result)) {
    $total_patients = $count_row['total'];
}

// Calculate total pages
$total_pages = ceil($total_patients / $limit);

// Define the range for the pagination (show 2 pages before and after the current page)
$page_range = 2;
$start_page = max(1, $page - $page_range);
$end_page = min($total_pages, $page + $page_range);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Patient - Lab Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styles */
		
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

        .action-btn {
            background-color: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .action-btn:hover {
            background-color: #27ae60;
        }

        .search-form {
            margin-top: 20px;
            text-align: center;
        }

        .search-form input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-form button {
            padding: 8px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #2980b9;
        }

        .pagination {
            margin: 20px 0;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #2980b9;
        }

        .pagination .current {
            background-color: #2ecc71;
        }

        .no-results {
            text-align: center;
            margin-top: 30px;
            font-size: 18px;
            color: #888;
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


<main>
<div class="table-container" style="margin-top: 20px;">


		<?php
// Assume you already get these in enter_result.php:
$mr_no = isset($_GET['mr_no']) ? $_GET['mr_no'] : '';
?>

<div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back to Dashboard</a>
</div>



    <h2 style="text-align: center;margin-top: 10px;margin-bottom: 10px;">Search Patient</h2>

    <form method="POST" action="search_patient.php" class="search-form">
        <input type="text" name="search" placeholder="Enter Patient Name or MR No" style="margin-top: -10px;margin-bottom: -5px;">
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($patients)) { ?>
        <table>
            <thead>
                <tr>
                    <th>MR No</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Age</th>
					<th>Referred By</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patients as $patient) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($patient['mr_no']); ?></td>
                        <td><?php echo htmlspecialchars($patient['name']); ?></td>
                        <td><?php echo htmlspecialchars($patient['phone']); ?></td>
                        <td><?php echo htmlspecialchars($patient['age']); ?></td>
						<td><?php echo htmlspecialchars($patient['referred_by']); ?></td>
						<td><?php echo htmlspecialchars($patient['gender']); ?></td>
                        <td>
                            <a href="patient_dashboard.php?mr_no=<?php echo urlencode($patient['mr_no']); ?>" class="action-btn">View</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>

            <?php 
            for ($i = $start_page; $i <= $end_page; $i++): 
                $class = ($i == $page) ? 'current' : ''; 
            ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $class; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>

    <?php } else { ?>
        <p class="no-results">No patients found!</p>
    <?php } ?>
	</DIV>
</main>


</body>
</html>
<?php include "footer.php"; ?>