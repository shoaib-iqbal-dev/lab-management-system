<?php
include('db.php');
include('header.php');
include "auth_check.php";

date_default_timezone_set("Asia/Karachi");

// Validate and sanitize receipt_id
if (!isset($_GET['receipt_id']) || empty($_GET['receipt_id'])) {
    echo "❌ Error: Receipt ID is missing!";
    include('footer.php');
    exit();
}

$receipt_id = $_GET['receipt_id'];

// Check if a test_name is passed in the URL
$test_name_to_show = isset($_GET['test_name']) ? $_GET['test_name'] : null;

// Test name validation (optional, for security purposes)
$valid_tests = ['cbc profile', 'lft', 'rft', 'esr', 'upt', 'electrolytes', 'amylase', 'crp', 'uric acid', 'bsr', 'malaria parasite', 'bsf', 'urine re', 'blood grouping', 'hep b', 'hep c', 'hiv', 'cardiac enzyme', 'dengue serology', 'dengue ns1', 'h pylori', 'ra factor', 'asot', 'calcium', 'phosphate', 'btct', 'pt', 'aptt', 'lipid profile', 'cholesterol', 'triglycerides', 'hba1c', 'albumin', 'serum vitamin b12', 'serum vitamin d', 'serum amh', 'serum prolactin', 'serum monomeric prolactin', 'serum ferritin', 'serum lh', 'serum fsh', 'alt', 'hdl', 'ldl', 'triglycerides', 'cholesterol', 'tg cholesterol', 'urea', 'creatinine', 'semen analysis', 'stool h pylori', 'thyroid profile'];
if ($test_name_to_show && !in_array(strtolower($test_name_to_show), $valid_tests)) {
    echo "⚠️ Invalid test selected.";
    include('footer.php');
    exit();
}

$stmt = $conn->prepare("
    SELECT 
        rt.test_name, 
        rt.receipt_id, 
        r.receipt_no, 
        r.mr_no, 
        p.name AS patient_name, 
        p.age, 
        p.gender, 
        p.phone AS contact_no, 
        p.referred_by
    FROM receipt_tests rt
    JOIN receipts r ON rt.receipt_id = r.receipt_id
    JOIN patients p ON r.mr_no = p.mr_no
    WHERE rt.receipt_id = ?
");

if (!$stmt) {
    die("❌ SQL Prepare Error: " . $conn->error);
}

$stmt->bind_param("s", $receipt_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "⚠️ No test found for this receipt ID.";
    include('footer.php');
    exit();
}

$tests = [];
$patient_info = null;

while ($row = $result->fetch_assoc()) {
    $tests[] = $row['test_name'];

    if ($patient_info === null) {
        $patient_info = [
            'receipt_no' => $row['receipt_no'],
            'mr_no' => $row['mr_no'],
            'patient_name' => $row['patient_name'],
            'age' => $row['age'],
            'gender' => $row['gender'],
            'contact_no' => $row['contact_no'],
            'referred_by' => $row['referred_by']
        ];
    }
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Test Results</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table.patient-table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 30px;
        }
        table.patient-table th, table.patient-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        table.patient-table th {
            background-color: #f2f2f2;
        }
        .form-section {
            border-top: 2px solid #007BFF;
            margin-top: 30px;
            padding-top: 15px;
        }
        h2 {
            color: #333;
        }
			header {
			background-color: #3498db;
			color: white;
			padding: 15px;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.logout-btn {
			color: white;
			background: #e74c3c;
			padding: 8px 12px;
			text-decoration: none;
			border-radius: 5px;
		}

		.header h1 {
			color: white; /* Make h1 text white */
		}

		.header h2 {
			color: white; /* Make h1 text white */
		}

		.header-link {
			text-decoration: none; /* Remove underline */
			color: inherit; /* Inherit color from h1 (which is now white) */
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

<body style="padding-right: 0px; padding-left: 0px; padding-top: 0px; padding-bottom: 0px;">

<?php
// Assume you already get these in enter_result.php:
$mr_no = isset($_GET['mr_no']) ? $_GET['mr_no'] : '';
?>

<div style="text-align: center; margin-top: 20px;">
    <a href="upload_result.php?mr_no=<?php echo urlencode($mr_no); ?>" class="back-button" style="margin-bottom: 0px;">Back to Test List</a>
</div>



    <h1 style="text-align:center;">Enter Test Results</h1>

    <!-- Patient Details Table -->
    <table class="patient-table">
        <tr>
            <td><strong>Receipt No</strong></td>
            <td><?= htmlspecialchars($patient_info['receipt_no']) ?></td>
            <td><strong>MR No</strong></td>
            <td><?= htmlspecialchars($patient_info['mr_no']) ?></td>
        </tr>
        <tr>
            <td><strong>Patient Name</strong></td>
            <td><?= htmlspecialchars($patient_info['patient_name']) ?></td>
            <td><strong>Gender</strong></td>
            <td><?= htmlspecialchars($patient_info['gender']) ?></td>
        </tr>
        <tr>
            <td><strong>Age</strong></td>
            <td><?= htmlspecialchars($patient_info['age']) ?> years</td>
            <td><strong>Contact No</strong></td>
            <td><?= htmlspecialchars($patient_info['contact_no']) ?></td>
        </tr>
        <tr>
            <td><strong>Referred By</strong></td>
            <td colspan="3"><?= htmlspecialchars($patient_info['referred_by']) ?></td>
        </tr>
    </table>

    <!-- Only display the form for the selected test -->
<?php if ($test_name_to_show): ?>

    <div class="form-section">
            <h2>Test: <?= htmlspecialchars($test_name_to_show) ?></h2>
            <?php
                switch (strtolower($test_name_to_show)) {
                    case 'cbc profile':
                        include('cbc_profile_form.php');
                        break;
                    case 'lft':
                        include('lft_form.php');
                        break;
                    case 'rft':
                        include('rft_form.php');
                        break;
                    case 'esr':
                        include('esr_form.php');
                        break;
                    case 'upt':
                        include('upt_form.php');
                        break;
                    case 'electrolytes':
                        include('electrolytes_form.php');
                        break;
                    case 'amylase':
                        include('amylase_form.php');
                        break;
                    case 'crp':
                        include('crp_form.php');
                        break;
                    case 'uric acid':
                        include('uric_acid_form.php');
                        break;
                    case 'bsr':
                        include('bsr_form.php');
                        break;
                    case 'bsf':
                        include('bsf_form.php');
                        break;
                    case 'urine re':
                        include('urine_re_form.php');
                        break;
                    case 'blood grouping':
                        include('blood_grouping_form.php');
                        break;
                    case 'hep b':
                        include('hep_b_form.php');
                        break;
                    case 'hep c':
                        include('hep_c_form.php');
                        break;
                    case 'hiv':
                        include('hiv_form.php');
                        break;
					case 'cardiac enzyme':
                        include('cardiac_enzyme_form.php');
                        break;
					case 'dengue serology':
                        include('dengue_serology_form.php');
                        break;
					case 'dengue ns1':
                        include('dengue_ns1_form.php');
                        break;
					case 'h pylori':
                        include('h_pylori_form.php');
                        break;
					case 'ra factor':
                        include('ra_factor_form.php');
                        break;
					case 'asot':
                        include('asot_form.php');
                        break;
					case 'calcium':
                        include('calcium_form.php');
                        break;
					case 'phosphate':
                        include('phosphate_form.php');
                        break;
					case 'btct':
                        include('btct_form.php');
                        break;
					case 'pt':
                        include('pt_form.php');
                        break;
					case 'aptt':
                        include('aptt_form.php');
                        break;
					case 'lipid profile':
                        include('lipid_profile_form.php');
                        break;
					case 'cholesterol':
                        include('cholesterol_form.php');
                        break;
					case 'triglycerides':
                        include('triglycerides_form.php');
                        break;
					case 'hba1c':
                        include('hba1c_form.php');
                        break;
					case 'albumin':
                        include('albumin_form.php');
                        break;
					case 'serum vitamin b12':
                        include('serum_vitamin_b12_form.php');
                        break;
					case 'serum vitamin d':
                        include('serum_vitamin_d_form.php');
                        break;
					case 'serum amh':
                        include('serum_amh_form.php');
                        break;
					case 'serum prolactin':
                        include('serum_prolactin_form.php');
                        break;
					case 'serum monomeric prolactin':
                        include('serum_monomeric_prolactin_form.php');
                        break;
					case 'serum ferritin':
                        include('serum_ferritin_form.php');
                        break;
					case 'serum lh':
                        include('serum_lh_form.php');
                        break;
					case 'serum fsh':
                        include('serum_fsh_form.php');
                        break;
					case 'thyroid profile':
                        include('thyroid_profile_form.php');
                        break;
					case 'alt':
                        include('alt_form.php');
                        break;
					case 'hdl':
                        include('hdl_form.php');
                        break;
					case 'ldl':
                        include('ldl_form.php');
                        break;
					case 'triglycerides':
                        include('triglycerides_form.php');
                        break;
					case 'cholesterol':
                        include('cholesterol_form.php');
                        break;
					case 'tg cholesterol':
                        include('tg_cholesterol_form.php');
                        break;
					case 'urea':
                        include('urea_form.php');
                        break;
					case 'creatinine':
                        include('creatinine_form.php');
                        break;
					case 'malaria parasite':
                        include('malaria_parasite_form.php');
                        break;
					case 'stool h pylori':
                        include('stool_h_pylori_form.php');
                        break;
					case 'semen analysis':
                        include('semen_analysis_form.php');
                        break;
                    default:
                        echo "<p>⚠️ No form available for <strong>" . htmlspecialchars($test_name_to_show) . "</strong>.</p>";
                }
            ?>
        </div>
    <?php else: ?>
        <p>⚠️ No test selected. Please select a test to view the form.</p>
    <?php endif; ?>

<?php include('footer.php'); ?>

</body>
</html>


