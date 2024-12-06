<?php
require '../dbconfig.php'; // Ensure you have a database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'update') {
        // Update Lab Test
        $testID = $_POST['TestID'];
        $testName = $_POST['TestName'];
        $fee = $_POST['Fee'];

        $stmt = $conn->prepare("UPDATE labtest SET TestName = ?, Fee = ? WHERE TestID = ?");
        $stmt->bind_param("sdi", $testName, $fee, $testID);

        if ($stmt->execute()) {
            header("Location: manage_labtest.php?message=updated");
        } else {
            header("Location: manage_labtest.php?message=error");
        }

        $stmt->close();
    } elseif ($action === 'add') {
        // Add New Lab Test
        $testName = $_POST['TestName'];
        $fee = $_POST['Fee'];

        $stmt = $conn->prepare("INSERT INTO labtest (TestName, Fee) VALUES (?, ?)");
        $stmt->bind_param("sd", $testName, $fee);

        if ($stmt->execute()) {
            header("Location: manage_labtest.php?message=added");
        } else {
            header("Location: manage_labtest.php?message=error");
        }

        $stmt->close();
    }
}
$conn->close();
?>
