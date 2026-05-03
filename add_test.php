<?php
session_start();
include "db.php";
include "header.php";
include "auth_check.php";

date_default_timezone_set("Asia/Karachi");

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get MR No
if (!isset($_GET['mr_no'])) {
    header("Location: search_patient.php");
    exit();
}

$mr_no = mysqli_real_escape_string($conn, $_GET['mr_no']);

// Get patient details
$patient_result = mysqli_query($conn, "SELECT * FROM patients WHERE mr_no = '$mr_no' LIMIT 1");
if (mysqli_num_rows($patient_result) == 0) {
    echo "Patient not found!";
    exit();
}
$patient = mysqli_fetch_assoc($patient_result);

// Get available tests
$test_result = mysqli_query($conn, "SELECT * FROM tests");
$tests = [];
while ($row = mysqli_fetch_assoc($test_result)) {
    $tests[] = $row;
}

// Step 1: Generate year part (YY)
$date_part = date('y'); // e.g., "25"

// Step 2: Count existing receipts for today
$query = "SELECT COUNT(*) as count FROM receipts WHERE receipt_no LIKE '{$date_part}%'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$sequence = $row['count'] + 1; // Next sequence number

// Step 3: Pad sequence with leading zeros to make it 3 digits
$sequence_str = str_pad($sequence, 3, '0', STR_PAD_LEFT); // e.g., "001"

// Step 4: Combine date part and sequence to form 6-digit receipt number
$receipt_no = $date_part . $sequence_str; // e.g., 250001

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Test - <?php echo htmlspecialchars($patient['name']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; }
        .patient-info {
            background: #f9f9f9;
            padding: 30px;
            margin: 20px auto;
            width: 90%;
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #3498db;
            color: white;
            text-align: left;
        }
        .test-actions { text-align: center; margin-top: 20px; }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover { background-color: #2980b9; }
        .btn-remove {
            background-color: #e74c3c;
            margin-left: 5px;
        }
        .btn-remove:hover { background-color: #c0392b; }
        .total-box {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
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

<div class="patient-info">

	<?php
// Assume you already get these in enter_result.php:
$mr_no = isset($_GET['mr_no']) ? $_GET['mr_no'] : '';
?>

<div style="text-align: center; margin-top: 20px;">
    <a href="patient_dashboard.php?mr_no=<?php echo urlencode($mr_no); ?>" class="back-button" style="margin-bottom: 0px;margin-top: -30px;">Back</a>
</div>

    <h2 style="text-align: center;">Patient Details</h2>
    <table>
        <tr>
            <th>MR No</th><td><?php echo htmlspecialchars($patient['mr_no']); ?></td>
            <th>Name</th><td><?php echo htmlspecialchars($patient['name']); ?></td>
        </tr>
        <tr>
			<th>Gender</th><td><?php echo htmlspecialchars($patient['gender']); ?></td>
			<th>Age</th><td><?php echo htmlspecialchars($patient['age']); ?></td>
        </tr>
        <tr>
             <th>Phone</th><td><?php echo htmlspecialchars($patient['phone']); ?></td>
			<th>Address</th><td><?php echo htmlspecialchars($patient['address']); ?></td>
		</tr>
        <tr>
    <th>Referred By</th><td><?php echo htmlspecialchars($patient['referred_by']); ?></td>
    </tr>

    </table>

    <h3 style="text-align: center;">Add Test</h3>
    <form id="testForm" method="POST" action="pay_bill.php">
        <input type="hidden" name="mr_no" value="<?php echo htmlspecialchars($patient['mr_no']); ?>">
        <input type="hidden" name="referred_by" value="<?php echo htmlspecialchars($patient['referred_by']); ?>">
		<input type="hidden" name="selected_tests" id="selectedTests">
        <input type="hidden" name="actual_fee" id="actualFee">
        <input type="hidden" name="discount" id="discount">
        <input type="hidden" name="total_fee" id="totalFeeInput">
		<input type="hidden" name="doctor_id" id="hiddenDoctorId">
		<input type="hidden" name="mr_no" value="<?php echo htmlspecialchars($patient['mr_no']); ?>">


<div class="test-actions">
    <label for="doctorSelect">Referred by:</label>
    <select name="doctor_id" id="doctorSelect">
        <option value="">-- Select --</option>
        <?php
        $doctors = mysqli_query($conn, "SELECT id, name FROM doctors");
        while ($doc = mysqli_fetch_assoc($doctors)) {
            echo "<option value='{$doc['id']}'>{$doc['name']}</option>";
        }
        ?>
    </select>
</div>

        <div class="test-actions">
            <label for="testSelect">Select Test:</label>
            <select id="testSelect">
                <option value="">-- Select --</option>
                <?php foreach ($tests as $test): ?>
                    <option value="<?php echo htmlspecialchars($test['test_name']); ?>" data-fee="<?php echo $test['fee']; ?>">
                        <?php echo htmlspecialchars($test['test_name']) . " - Rs. " . $test['fee']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="btn" onclick="addTest()">Add Test</button>
        </div>

        <h3 style="text-align: center;">Selected Tests</h3>
        <table id="testTable">
            <thead>
                <tr><th>Test Name</th><th>Fee (Rs.)</th><th>Action</th></tr>
            </thead>
            <tbody></tbody>
        </table>

        <div class="total-box">
            Total: Rs. <span id="totalFee">0</span>
        </div>

        <div class="test-actions">
            <label for="discountInput">Discount:</label>
            <input type="number" id="discountInput" value="0">
            <button type="button" class="btn" onclick="applyDiscount()">Calculate Discount</button>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <button type="submit" class="btn">Pay Bill</button>
        </div>
		
		

		
    </form>
</div>

<script>
let totalFee = 0;
let selectedTests = [];
let actualFee = 0;

function addTest() {
    const select = document.getElementById("testSelect");
    const testName = select.value;
    const testFee = parseInt(select.options[select.selectedIndex].getAttribute("data-fee"));

    if (!testName) {
        alert("Please select a test.");
        return;
    }

    if (selectedTests.includes(testName)) {
        alert("Test already added.");
        return;
    }

    const table = document.getElementById("testTable").getElementsByTagName('tbody')[0];
    const row = table.insertRow();

    const nameCell = row.insertCell(0);
    const feeCell = row.insertCell(1);
    const actionCell = row.insertCell(2);

    nameCell.textContent = testName;
    feeCell.textContent = testFee;

    const removeBtn = document.createElement("button");
    removeBtn.textContent = "Remove";
    removeBtn.className = "btn btn-remove";
    removeBtn.onclick = function () {
        row.remove();
        selectedTests = selectedTests.filter(t => t !== testName);
        actualFee -= testFee;
        totalFee -= testFee;
        updateTotal();
    };
    actionCell.appendChild(removeBtn);

    selectedTests.push(testName);
    actualFee += testFee;
    totalFee += testFee;
    updateTotal();

    select.selectedIndex = 0;
}

function applyDiscount() {
    const discount = parseInt(document.getElementById("discountInput").value);
    const discountedFee = totalFee - discount;

    if (discountedFee < 0) {
        alert("Discount can't be more than the total fee.");
        return;
    }

    totalFee = discountedFee;
    updateTotal();
    document.getElementById("discount").value = discount;
}

function updateTotal() {
    document.getElementById("totalFee").textContent = totalFee;
    document.getElementById("actualFee").value = actualFee;
    document.getElementById("totalFeeInput").value = totalFee;
}

document.getElementById("testForm").addEventListener("submit", function (e) {
    if (selectedTests.length === 0) {
        e.preventDefault();
        alert("Please add at least one test before proceeding.");
        return;
    }
	
	//remove bad me agar na chala to
	// Set hidden doctor ID before submitting
    const selectedDoctor = document.getElementById("doctorSelect").value;
    document.getElementById("hiddenDoctorId").value = selectedDoctor;
	
	//yha tk

    document.getElementById("selectedTests").value = JSON.stringify(selectedTests);
});
</script>

</body>
</html>

<?php include "footer.php"; ?>
