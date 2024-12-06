<?php
include '../dbconfig.php';
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Check if labTestID is provided in the URL
if (isset($_GET['labTestID'])) {
    $labTestID = $_GET['labTestID'];

    // Fetch lab test details using the labTestID
    $query = "
        SELECT 
            bt.LabTestID,
            bt.TestID, 
            bt.Fees, 
            bt.Date_Time, 
            bt.Result, 
            bt.PatientID, 
            bt.BedID,
            lt.TestName,
            p.FirstName, 
            p.LastName
        FROM 
            book_labtest bt
        JOIN 
            labtest lt ON bt.TestID = lt.TestID
        JOIN 
            patient p ON bt.PatientID = p.PatientID
        WHERE 
            bt.LabTestID = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $labTestID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the lab test details are found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fees = $row['Fees'];
        $test_name = $row['TestName'];
        $patient_name = $row['FirstName'] . ' ' . $row['LastName'];
        $date_time = $row['Date_Time'];
        $results = $row['Result'];
        $bed_id = $row['BedID'];
    } else {
        echo "<script>alert('No lab test found with the provided ID.'); window.location.href = 'labtest.php';</script>";
        exit;
    }

    $stmt->close();
} else {
    echo "<script>alert('Lab Test ID not provided in the URL.'); window.location.href = 'labtest.php';</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Test Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .receipt {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 450px;
            max-width: 100%;
            text-align: left;
        }

        .receipt h2 {
            margin-bottom: 20px;
            color: #4CAF50;
            font-size: 28px;
            text-align: center;
        }

        .receipt p {
            font-size: 16px;
            color: #333;
            margin: 10px 0;
            line-height: 1.6;
        }

        .receipt .bold {
            font-weight: bold;
            color: #333;
        }

        .receipt .receipt-footer {
            margin-top: 25px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }

        .receipt .details {
            border-top: 2px solid #4CAF50;
            margin-top: 20px;
            padding-top: 15px;
        }

        .receipt .details p {
            display: flex;
            justify-content: space-between;
        }

        .receipt .details p span {
            font-weight: normal;
        }

        .print-btn {
            margin-top: 25px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 30px;
            display: block;
            width: 100%;
            transition: background-color 0.3s;
        }

        .print-btn:hover {
            background-color: #45a049;
        }

        .print-btn i {
            margin-right: 10px;
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .receipt-header .patient-info {
            font-size: 16px;
            color: #555;
        }

        .receipt-header .patient-info span {
            font-weight: bold;
        }

    </style>
</head>
<body>

<div class="receipt">
    <div class="receipt-header">
        <div class="patient-info">
            <p><span>Patient Name:</span> <?php echo htmlspecialchars($patient_name); ?></p>
            <p><span>Bed ID:</span> <?php echo htmlspecialchars($bed_id); ?></p>
        </div>
        <div class="patient-info">
            <p><span>Test Date:</span> <?php echo htmlspecialchars($date_time); ?></p>
        </div>
    </div>
    
    <h2>Lab Test Receipt</h2>
    <div class="details">
        <p><span class="bold">Test Name:</span> <?php echo htmlspecialchars($test_name); ?></p>
        <p><span class="bold">Test Results:</span> <?php echo htmlspecialchars($results); ?></p>
        <p><span class="bold">Fees:</span> Rs. <?php echo number_format($fees, 2); ?></p>
    </div>

    <div class="receipt-footer">
        <p>Thank you for choosing our services!</p>
    </div>
    <button class="print-btn" onclick="window.print()">
        <i class="fa fa-print"></i> Print Receipt
    </button>
</div>

</body>
</html>
