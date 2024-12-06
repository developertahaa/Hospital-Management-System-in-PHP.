<?php
// Database Connection
include '../dbconfig.php';

// Fetch all appointments
$appointmentsQuery = "
    SELECT 
        a.AppointmentID AS appointment_id,
        a.timeslot AS appointment_time,
        a.Date as appointment_date,
        d.FirstName AS doctor_name,
        a.Fee AS doctor_fees,
        p.FirstName AS patient_name,
        p.LastName AS patient_last_name
    FROM 
        appointment a
    JOIN 
        doctor d 
    ON 
        a.DoctorID = d.DoctorID
    JOIN 
        patient p 
    ON 
        a.PatientID = p.PatientID
    ORDER BY 
        a.Date DESC";
$appointmentsResult = $conn->query($appointmentsQuery);

// Store appointments in an array
$appointments = [];
while ($row = $appointmentsResult->fetch_assoc()) {
    $appointments[] = $row;
}

$labtestinvoice = "
SELECT bk.labTestID, bk.TestID, bk.PatientID, bk.fees, bk.Date_Time, p.FirstName, l.TestName, p.LastName
from book_labtest bk
JOIN 
labtest l
ON
bk.TestID = l.TestID
JOIN 
patient p
ON
bk.PatientID = p.PatientID
ORDER BY 
bk.Date_Time DESC";

$labresult = $conn->query($labtestinvoice);

$booktests = [];
while($testrow = $labresult->fetch_assoc()){
    $booktests[] = $testrow;
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    /* General styling for the modal */
    .invoice-modal .modal-content {
        padding: 20px;
        border-radius: 15px;
        border: 1px solid #dee2e6;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Header styles */
    .invoice-modal .invoice-header {
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 10px;
    }

    .invoice-modal .invoice-header h4 {
        font-weight: bold;
        font-size: 24px;
        color: #007bff;
    }

    .invoice-modal .invoice-header small {
        font-size: 14px;
        color: #6c757d;
    }

    /* Details container styling */
    .invoice-details {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        margin-bottom: 20px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
    }

    .invoice-details h6 {
        font-weight: bold;
        margin-bottom: 15px;
        font-size: 18px;
        color: #333;
    }

    .invoice-details p {
        margin: 0 0 10px;
        font-size: 16px;
        color: #555;
    }

    .invoice-details p span {
        font-weight: bold;
        color: #000;
    }

    /* Print button styling */
    .print-button {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        transition: all 0.3s;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
    }

    .print-button:hover {
        background-color: #0056b3;
    }

    /* Custom typography */
    .modal-title {
        font-weight: bold;
        color: #333;
    }

    /* Make modal responsive */
    @media screen and (max-width: 768px) {
        .invoice-details {
            padding: 15px;
        }

        .invoice-modal .modal-content {
            padding: 15px;
        }
    }
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="container main-content">
    <h2>Invoices</h2>
    
    <!-- Appointments Table -->
    <h3>Appointment Invoices</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Doctor Name</th>
                <th>Patient Name</th>
                <th>Date & Time</th>
                <th>Fees</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['appointment_id']) ?></td>
                    <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['patient_name'] . ' ' . $appointment['patient_last_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                    <td><?= htmlspecialchars($appointment['doctor_fees']) ?></td>
                    <td>
                        <button 
                            class="btn btn-primary generate-invoice-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#invoiceModal" 
                            data-doctor-name="<?= htmlspecialchars($appointment['doctor_name']) ?>" 
                            data-patient-name="<?= htmlspecialchars($appointment['patient_name'] . ' ' . $appointment['patient_last_name']) ?>" 
                            data-timeslot="<?= htmlspecialchars($appointment['appointment_time']) ?>" 
                            data-date="<?= htmlspecialchars($appointment['appointment_date']) ?>" 
                            data-fees="<?= htmlspecialchars($appointment['doctor_fees']) ?>">
                            Generate Invoice
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Lab Test Table -->
    <h3>Lab Test Invoices</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Lab Test ID</th>
                <th>Test Name</th>
                <th>Patient Name</th>
                <th>Date & Time</th>
                <th>Fees</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($booktests as $testrow): ?>
                <tr>
                    <td><?= htmlspecialchars($testrow['labTestID']) ?></td>
                    <td><?= htmlspecialchars($testrow['TestName']) ?></td>
                    <td><?= htmlspecialchars($testrow['FirstName'] . ' ' . $testrow['LastName']) ?></td>
                    <td><?= htmlspecialchars($testrow['Date_Time']) ?></td>
                    <td><?= htmlspecialchars($testrow['fees']) ?></td>
                    <td>
                        <button 
                            class="btn btn-primary generate-lab-invoice-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#labInvoiceModal" 
                            data-labtest-id="<?= htmlspecialchars($testrow['labTestID']) ?>" 
                            data-test-name="<?= htmlspecialchars($testrow['TestName']) ?>" 
                            data-patient-name="<?= htmlspecialchars($testrow['FirstName'] . ' ' . $testrow['LastName']) ?>" 
                            data-date="<?= htmlspecialchars($testrow['Date_Time']) ?>" 
                            data-fees="<?= htmlspecialchars($testrow['fees']) ?>" >
                            Generate Invoice
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<!-- Invoice Modal for Lab Tests -->
<div class="modal fade invoice-modal" id="labInvoiceModal" tabindex="-1" aria-labelledby="labInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labInvoiceModalLabel">Lab Test Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="labInvoiceContent">
                <div class="invoice-header">
                    <h4>Hospital Management System</h4>
                    <small>Invoice</small>
                </div>
                <div class="invoice-details">
                    <h6>Lab Test Details:</h6>
                    <p><strong>Test Name:</strong> <span id="labInvoiceTestName"></span></p>
                    <p><strong>Patient Name:</strong> <span id="labInvoicePatientName"></span></p>
                    <p><strong>Date & Time:</strong> <span id="labInvoiceDate"></span></p>
                    <p><strong>Fees:</strong> Rs. <span id="labInvoiceFees"></span></p>
                </div>
                <button class="print-button" onclick="printLabInvoice()">Print Invoice</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Populate the modal with appointment details
    document.querySelectorAll('.generate-invoice-btn').forEach(button => {
        button.addEventListener('click', function () {
            const doctorName = this.getAttribute('data-doctor-name');
            const patientName = this.getAttribute('data-patient-name');
            const timeslot = this.getAttribute('data-timeslot');
            const date = this.getAttribute('data-date');
            const fees = this.getAttribute('data-fees');

            document.getElementById('invoiceDoctorName').textContent = doctorName;
            document.getElementById('invoicePatientName').textContent = patientName;
            document.getElementById('invoiceTimeslot').textContent = timeslot;
            document.getElementById('invoiceDate').textContent = date;
            document.getElementById('invoiceFees').textContent = fees;
        });
    });

    document.querySelectorAll('.generate-lab-invoice-btn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('labInvoiceTestName').textContent = this.getAttribute('data-test-name');
            document.getElementById('labInvoicePatientName').textContent = this.getAttribute('data-patient-name');
            document.getElementById('labInvoiceDate').textContent = this.getAttribute('data-date');
            document.getElementById('labInvoiceFees').textContent = this.getAttribute('data-fees');
            document.getElementById('labInvoiceResult').textContent = this.getAttribute('data-result');
        });
    });

    // Print invoice functionality
    function printInvoice() {
        const content = document.getElementById('invoiceContent').innerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = content;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    }
    function printLabInvoice() {
        const invoiceContent = document.getElementById('labInvoiceContent').innerHTML;
        const originalContent = document.body.innerHTML;
        document.body.innerHTML = invoiceContent;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    }
    
</script>
</body>
</html>
