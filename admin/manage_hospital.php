<?php
// Include database connection
include '../dbconfig.php';

// Fetch all rooms and their types
$roomsQuery = "
    SELECT r.RoomID, rt.RoomType, r.room_type_id
    FROM room r
    JOIN roomtype rt ON r.room_type_id = rt.RoomID";
$roomsResult = $conn->query($roomsQuery);

$rooms = [];
while ($row = $roomsResult->fetch_assoc()) {
    $roomId = $row['RoomID'];
    $rooms[$roomId] = ['type' => $row['RoomType'], 'beds' => []];

    // Fetch beds for each room
    $bedsQuery = "SELECT * FROM bed WHERE RoomID = $roomId";
    $bedsResult = $conn->query($bedsQuery);
    while ($bed = $bedsResult->fetch_assoc()) {
        $rooms[$roomId]['beds'][] = $bed;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .room-card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
        }

        .room-card h4 {
            margin-bottom: 10px;
            color: #495057;
            font-size: 20px;
        }

        .room-type {
            font-size: 18px;
            color: #007bff;
            font-weight: bold;
        }

        .bed-item {
            margin-bottom: 8px;
            padding: 10px;
            border-radius: 8px;
            background-color: #e9ecef;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-add {
            background-color: #28a745;
            color: #fff;
            font-size: 14px;
            border-radius: 30px;
            width: 100%;
        }

        .btn-add:hover {
            background-color: #218838;
        }

        .modal-header {
            background-color: #007bff;
            color: white;
        }

        .modal-body {
            background-color: #f8f9fa;
        }

        .modal-footer button {
            border-radius: 30px;
        }

        .room-card .list-group-item {
            border: none;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .btn-icon {
            background-color: #17a2b8;
            border-radius: 50%;
            padding: 10px;
            margin-top: 10px;
            color: white;
        }

        .btn-icon:hover {
            background-color: #138496;
        }

        .fa-bed {
            font-size: 24px;
        }

        .fa-plus-circle {
            font-size: 30px;
            color: #28a745;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <h2 class="mb-4">Manage Hospital Rooms</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addRoomModal">
        <i class="fa fa-plus-circle"></i> Add New Room
    </button>

    <!-- Display Rooms and Beds -->
    <div class="row">
        <?php foreach ($rooms as $roomId => $room): ?>
            <div class="col-md-6">
                <div class="room-card">
                    <h4><i class="fa fa-bed"></i> Room ID: <?= htmlspecialchars($roomId) ?></h4>
                    <p class="room-type"><?= htmlspecialchars($room['type']) ?></p>
                    <ul class="list-group">
                        <?php foreach ($room['beds'] as $bed): ?>
                            <li class="list-group-item bed-item">
                                <i class="fa fa-bed"></i> Bed ID: <?= htmlspecialchars($bed['BedID']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <button class="btn btn-add mt-3" data-bs-toggle="modal" data-bs-target="#confirmationModal-<?= $roomId ?>">
                        <i class="fa fa-plus-circle"></i> Add Bed
                    </button>
                </div>
            </div>

            <!-- Add Bed Confirmation Modal -->
            <div class="modal fade" id="confirmationModal-<?= $roomId ?>" tabindex="-1" aria-labelledby="confirmationModalLabel-<?= $roomId ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="add_room.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmationModalLabel-<?= $roomId ?>">Confirm Add Bed to Room <?= htmlspecialchars($roomId) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to add a new bed to this room?</p>
                                <input type="hidden" name="room_id" value="<?= $roomId ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="add_room.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoomModalLabel">Add New Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="roomType" class="form-label">Room Type</label>
                        <select class="form-control" name="room_type_id" id="roomType" required>
                            <?php
                            include '../dbconfig.php';
                            $typesQuery = "SELECT * FROM roomtype";
                            $typesResult = $conn->query($typesQuery);
                            while ($type = $typesResult->fetch_assoc()): ?>
                                <option value="<?= $type['RoomID'] ?>"><?= htmlspecialchars($type['RoomType']) ?></option>
                            <?php endwhile; ?>
                            <?php $conn->close(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="Capacity" class="form-label">Capacity</label>
                        <input type="number" class="form-control" name="capacity" required>
                    </div>
                    <div class="mb-3">
                        <label for="charges" class="form-label">Charges</label>
                        <input type="number" class="form-control" name="charges" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
