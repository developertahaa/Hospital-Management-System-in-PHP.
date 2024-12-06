<?php
// Include database connection
include '../dbconfig.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemId = $_POST['item_id'];
    $itemName = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $reOrderLevel = $_POST['reorder_level'];

    // Update inventory item
    $query = "UPDATE inventory SET ItemName = ?, Quantity = ?, ReOrderLevel = ? WHERE ItemID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("siii", $itemName, $quantity, $reOrderLevel, $itemId);
    $stmt->execute();

    // Close connection
    $stmt->close();
    $conn->close();

    // Redirect back to inventory page
    header("Location: inventory.php");
    exit();
}
?>
