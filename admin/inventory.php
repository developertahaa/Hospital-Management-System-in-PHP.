<?php
// Include database connection
include '../dbconfig.php';

// Fetch all inventory items
$inventoryQuery = "SELECT * FROM inventory";
$inventoryResult = $conn->query($inventoryQuery);

$inventoryItems = [];
while ($item = $inventoryResult->fetch_assoc()) {
    $inventoryItems[] = $item;
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .inventory-card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
        }

        .inventory-card h4 {
            margin-bottom: 10px;
            color: #6c757d;
        }

        .inventory-card .btn {
            font-size: 14px;
        }

        .btn-add {
            background-color: #007bff;
            color: #fff;
        }

        .btn-add:hover {
            background-color: #0056b3;
        }

        .btn-update {
            background-color: #ffc107;
            color: #fff;
        }

        .btn-update:hover {
            background-color: #e0a800;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<?php
include 'sidebar.php';
?>

<div class="main-content">
    <h2 class="mb-3">Manage Inventory</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addItemModal"><i class="fas fa-plus"></i> Add New Item</button>

    <!-- Display Inventory Items -->
    <div class="row">
        <?php foreach ($inventoryItems as $item): ?>
            <div class="col-md-6 col-lg-4">
                <div class="inventory-card">
                    <h4>Item ID: <?= htmlspecialchars($item['ItemID']) ?></h4>
                    <p><strong>Item Name:</strong> <?= htmlspecialchars($item['ItemName']) ?></p>
                    <p><strong>Quantity:</strong> <?= htmlspecialchars($item['Quantity']) ?></p>
                    <p><strong>ReOrder Level:</strong> <?= htmlspecialchars($item['ReOrderLevel']) ?></p>

                    <!-- Update Button -->
                    <button class="btn btn-update" data-bs-toggle="modal" data-bs-target="#updateItemModal-<?= $item['ItemID'] ?>"><i class="fas fa-edit"></i> Update</button>
                    <!-- Delete Button (optional) -->
                    <button class="btn btn-delete"><i class="fas fa-trash-alt"></i> Delete</button>
                </div>
            </div>

            <!-- Update Item Modal -->
            <div class="modal fade" id="updateItemModal-<?= $item['ItemID'] ?>" tabindex="-1" aria-labelledby="updateItemModalLabel-<?= $item['ItemID'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="update_inventory.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateItemModalLabel-<?= $item['ItemID'] ?>">Update Item - <?= htmlspecialchars($item['ItemName']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="item_id" value="<?= $item['ItemID'] ?>">

                                <div class="mb-3">
                                    <label for="itemName" class="form-label">Item Name</label>
                                    <input type="text" class="form-control" name="item_name" value="<?= htmlspecialchars($item['ItemName']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" name="quantity" value="<?= htmlspecialchars($item['Quantity']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="reOrderLevel" class="form-label">ReOrder Level</label>
                                    <input type="number" class="form-control" name="reorder_level" value="<?= htmlspecialchars($item['ReOrderLevel']) ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Item</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="add_inventory.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Item Name</label>
                        <input type="text" class="form-control" name="item_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="reOrderLevel" class="form-label">ReOrder Level</label>
                        <input type="number" class="form-control" name="reorder_level" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
