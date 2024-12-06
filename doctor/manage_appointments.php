<?php
// Start session
session_start();

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Doctor') {
    echo "<script>alert('Access denied!'); window.location.href='../index.php';</script>";
    exit;
}

// Include database connection
include '../dbconfig.php';

// Get doctor ID from the session
$userID = $_SESSION['user_id'];

$query = "SELECT DoctorID FROM doctor WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $doctorRow = $result->fetch_assoc();
    $doctorID = $doctorRow['DoctorID'];
} else {
    echo "<script>alert('Doctor record not found!'); window.location.href='../index.php';</script>";
    exit;
}
// Fetch appointments for the logged-in doctor
$query = "SELECT a.AppointmentID, a.PatientID, a.DoctorID, a.Date, a.Fee, a.Status, a.timeslot, 
                 p.FirstName AS PatientFirstName, p.LastName AS PatientLastName 
          FROM appointment a
          JOIN patient p ON a.PatientID = p.PatientID
          WHERE a.DoctorID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctorID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .table-actions button {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="container main-content">
        <h2 class="text-center">Manage Appointments</h2>
        <p class="text-center text-secondary">Here are your scheduled appointments.</p>

        <table class="table table-hover table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Timeslot</th>
                    <th>Fee</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['AppointmentID']); ?></td>
                    <td><?php echo htmlspecialchars($row['PatientFirstName'] . ' ' . $row['PatientLastName']); ?></td>
                    <td><?php echo htmlspecialchars($row['Date']); ?></td>
                    <td><?php echo htmlspecialchars($row['timeslot']); ?></td>
                    <td><?php echo htmlspecialchars($row['Fee']); ?></td>
                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                    <td class="table-actions">
                        <form method="POST" action="update_appointment_status.php" style="display: inline-block;">
                            <input type="hidden" name="appointment_id" value="<?php echo $row['AppointmentID']; ?>">
                            <select name="status" class="form-select-sm" required>
                                <option value="Pending" <?php echo $row['Status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Missed" <?php echo $row['Status'] === 'Missed' ? 'selected' : ''; ?>>Missed</option>
                                <option value="Cancelled" <?php echo $row['Status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                <option value="Completed" <?php echo $row['Status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>

                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if ($result->num_rows === 0): ?>
            <p class="text-center text-muted">No appointments found.</p>
        <?php endif; ?>

        <?php
        $stmt->close();
        $conn->close();
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
