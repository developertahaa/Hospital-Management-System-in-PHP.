<?php
// Database Connection
include '../dbconfig.php';

// Fetch Patients and Their Appointments
$patientsQuery = "
    SELECT 
        p.PatientID AS patient_id,
        p.FirstName AS patient_name,
        a.AppointmentID AS appointment_id,
        a.Date AS appointment_date,
        a.timeslot as appointment_timeslot,
        a.status AS appointment_status,
        a.Fee AS appointment_fee
    FROM 
        patient p
    JOIN 
        appointment a 
    ON 
        p.PatientID = a.PatientID
    ORDER BY a.Date DESC";
$patientsResult = $conn->query($patientsQuery);

$patients = [];
while ($row = $patientsResult->fetch_assoc()) {
    $patients[] = $row;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
     

       
        .main-content h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .search-bar input {
            border-radius: 20px;
            padding: 10px 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            border-collapse: separate;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #4CAF50;
            color: #fff;
        }

        .table thead th {
            text-align: center;
            padding: 15px;
        }

        .table tbody tr {
            transition: all 0.2s ease-in-out;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .status-btn {
            border: none;
            border-radius: 20px;
            padding: 8px 15px;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
        }

        .status-btn.cancelled {
            background-color: #f44336;
        }

        .status-btn.completed {
            background-color: #4CAF50;
        }

        .status-btn.missed {
            background-color: #ffc107;
        }

        .status-btn.pending {
            background-color: #2196F3;
        }
    </style>
</head>
<body>
<?php
include 'sidebar.php';
?>
<div class="main-content">
    <h2>Manage Appointments</h2>
    <div class="search-bar">
        <input type="text" id="searchInput" class="form-control" placeholder="Search patients by name or email...">
    </div>
    <div class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Appointment Date</th>
                    <th>Appointment TimeSlot</th>
                    <th>Status</th>
                    <th>Fee</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="patientsTable">
                <?php foreach ($patients as $patient): ?>
                    <tr>
                        <td><?= htmlspecialchars($patient['patient_name']) ?></td>
                        <td><?= $patient['appointment_date'] ?></td>
                        <td><?= $patient['appointment_timeslot'] ?></td>
                        <td>
                            <span class="status-btn <?= strtolower($patient['appointment_status']) ?>">
                                <?= ucfirst($patient['appointment_status']) ?>
                            </span>
                        </td>
                        <td>Rs. <?= number_format($patient['appointment_fee'], 2) ?></td>
                        <td>
                            <select class="form-select form-select-sm changeStatus" data-appointment-id="<?= $patient['appointment_id'] ?>">
                                <option value="Pending" <?= $patient['appointment_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Cancelled" <?= $patient['appointment_status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                <option value="Completed" <?= $patient['appointment_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="Missed" <?= $patient['appointment_status'] == 'Missed' ? 'selected' : '' ?>>Missed</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Search Filter
    $('#searchInput').on('keyup', function () {
        const value = $(this).val().toLowerCase();
        $('#patientsTable tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Change Appointment Status
    // Change Appointment Status
$('.changeStatus').on('change', function () {
    const appointmentId = $(this).data('appointment-id');
    const newStatus = $(this).val();

    $.ajax({
        url: 'update_status.php', // Updated to new file
        type: 'POST',
        data: {
            appointment_id: appointmentId,
            status: newStatus
        },
        success: function (response) {
            const result = JSON.parse(response);
            alert(result.message);
            location.reload();
        },
        error: function () {
            alert('Error updating status. Please try again.');
        }
    });
});

</script>

</body>
</html>
