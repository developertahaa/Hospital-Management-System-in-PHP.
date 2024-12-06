<?php
include '../dbconfig.php'; // Database configuration and connection

// Fetch lab tests from the database
$labtestquery = "SELECT * FROM labtest";
$tests = [];
$result = mysqli_query($conn, $labtestquery); // Execute the query

if ($result && mysqli_num_rows($result) > 0) {
    // Fetch data into an array
    while ($row = mysqli_fetch_assoc($result)) {
        $tests[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lab Tests</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    include 'sidebar.php'; // Include the sidebar if necessary
    ?>
<div class="container main-content">
    <h3 class="mb-4 text-center">Manage Lab Tests</h3>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addTestModal">
        <i class="fas fa-plus"></i> Add New Test
    </button>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Test ID</th>
                <th>Test Name</th>
                <th>Fee</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tests)): ?>
                <?php foreach ($tests as $test): ?>
                    <tr>
                        <td><?= htmlspecialchars($test['TestID']) ?></td>
                        <td><?= htmlspecialchars($test['TestName']) ?></td>
                        <td><?= htmlspecialchars($test['Fee']) ?></td>
                        <td>
                            <!-- Update Button -->
                            <button 
                                class="btn btn-warning update-test-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#updateTestModal" 
                                data-test-id="<?= htmlspecialchars($test['TestID']) ?>" 
                                data-test-name="<?= htmlspecialchars($test['TestName']) ?>" 
                                data-fee="<?= htmlspecialchars($test['Fee']) ?>">
                                <i class="fas fa-edit"></i> Update
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No lab tests found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Test Modal -->
<div class="modal fade" id="addTestModal" tabindex="-1" aria-labelledby="addTestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTestModalLabel">Add New Lab Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="update_labtest.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addTestName" class="form-label">Test Name</label>
                        <input type="text" class="form-control" id="addTestName" name="TestName" required>
                    </div>
                    <div class="mb-3">
                        <label for="addFee" class="form-label">Fee</label>
                        <input type="number" class="form-control" id="addFee" name="Fee" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="btn btn-success">Add Test</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Test Modal -->
<div class="modal fade" id="updateTestModal" tabindex="-1" aria-labelledby="updateTestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateTestModalLabel">Update Lab Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="update_labtest.php">
                <div class="modal-body">
                    <input type="hidden" name="TestID" id="updateTestID">
                    <div class="mb-3">
                        <label for="updateTestName" class="form-label">Test Name</label>
                        <input type="text" class="form-control" id="updateTestName" name="TestName" required>
                    </div>
                    <div class="mb-3">
                        <label for="updateFee" class="form-label">Fee</label>
                        <input type="number" class="form-control" id="updateFee" name="Fee" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" value="update">
                    <button type="submit" class="btn btn-warning">Update Test</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.update-test-btn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('updateTestID').value = this.getAttribute('data-test-id');
            document.getElementById('updateTestName').value = this.getAttribute('data-test-name');
            document.getElementById('updateFee').value = this.getAttribute('data-fee');
        });
    });
</script>
</body>
</html>
