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

// Function to generate MR number like 2504001
function generateNextMRNo($conn) {
    $year = date("y");    // '25' for 2025
    $month = date("m");   // '04' for April

    // Count how many patients added in this year+month using created_at column
    $query = "SELECT COUNT(*) AS count FROM patients WHERE DATE_FORMAT(created_at, '%y%m') = '$year$month'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'] + 1;

    // Format: YYMMXXXXX (no dash)
    return $year . $month . str_pad($count, 3, '0', STR_PAD_LEFT);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] . '  ' . $_POST['father_name'];
    $age = $_POST['age'];
	$age_unit = $_POST['age_unit'];
	$age_display = $age . ' ' . $age_unit;
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $referred_by = $_POST['referred_by'];


    

    // Generate MR number
    $mr_no = generateNextMRNo($conn);

    // Insert patient data into the database
	$insertQuery = "INSERT INTO patients (mr_no, name, age, gender, address, phone, referred_by, created_at)
	VALUES ('$mr_no', '$name', '$age_display', '$gender', '$address', '$phone', '$referred_by', NOW())";


    if (mysqli_query($conn, $insertQuery)) {
        // Redirect to patient dashboard after adding patient
        header("Location: patient_dashboard.php?mr_no=" . urlencode($mr_no));
        exit();
    } else {
        echo "<script>alert('❌ Error: " . mysqli_error($conn) . "'); window.history.back();</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Patient - Lab Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .add-patient-container {
            max-width: 600px;
            margin: 15px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .add-patient-container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #3498db;
        }
        .add-patient-container form {
            display: flex;
            flex-direction: column;
        }
        .add-patient-container label {
            margin-top: 10px;
            font-weight: bold;
        }
        .add-patient-container input,
        .add-patient-container select,
        .add-patient-container textarea {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .add-patient-container button {
            margin-top: 20px;
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .add-patient-container button:hover {
            background-color: #2980b9;
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
    <div class="add-patient-container">
	
	<div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Back to Dashboard</a>
	    <a href="search_patient.php" class="back-button" style="margin-bottom: 0px;margin-top: -20px;">Search Patient</a>
</div>
	
        <h2>Add New Patient</h2>

        <form method="POST" action="add_patient.php">
            <label for="name">Patient Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="father_name">Father / Husband's Name:</label>
            <input type="text" id="father_name" name="father_name" required>

            <label for="age">Age:</label>
<div style="display: flex; gap: 10px;">
    <input type="number" id="age" name="age" min="0" required placeholder="Enter age">
    <select name="age_unit" required>
        <option value="Years">Years</option>
        <option value="Months">Months</option>
        <option value="Days">Days</option>
    </select>
</div>


            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="3" ></textarea>

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" maxlength="11" required
       pattern="03[0-9]{9}" title="Phone number must start with 03 and be 11 digits long">

            <label for="referred_by">Referred By Doctor:</label>
<input type="text" id="referred_by" name="referred_by" required
title="Enter the name of the doctor who referred the patient">


            <button type="submit">Save Patient</button>
        </form>
    </div>
</main>

</body>
</html>

<?php include "footer.php"; ?>
