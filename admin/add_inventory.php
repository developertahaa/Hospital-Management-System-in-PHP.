<?php
// Include database connection
include '../dbconfig.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemName = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $reOrderLevel = $_POST['reorder_level'];

    // Insert new inventory item
    $query = "INSERT INTO inventory (ItemName, Quantity, ReOrderLevel) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $itemName, $quantity, $reOrderLevel);
    $stmt->execute();

    // Close connection
    $stmt->close();
    $conn->close();

    // Redirect back to inventory page
    header("Location: inventory.php");
    exit();
}
?>
