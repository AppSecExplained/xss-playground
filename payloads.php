<?php
require 'db.php';

// Fetch payloads from the database
$result = $conn->query("SELECT * FROM payloads");
$payloads = $result->fetch_all(MYSQLI_ASSOC);
// Fetch actions from the database
$action_result = $conn->query("SELECT * FROM actions ORDER BY datetime_stamp DESC");
$actions = $action_result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Payloads</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="my-4">Available XSS Payloads</h1>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Payload</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payloads as $payload): ?>
                    <tr>
                        <th scope="row"><?= $payload['payload_id'] ?></th>
                        <td><?= htmlspecialchars($payload['payload']) ?></td>
                        <td><?= htmlspecialchars($payload['description']) ?></td>
                        <td>
                            <form action="victim.php" method="GET">
                                <input type="hidden" name="payload_id" value="<?= $payload['payload_id'] ?>">
                                <button type="submit" class="btn btn-primary">Select</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h2 class="my-4">Actions Log</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Action</th>
                    <th scope="col">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($actions as $action): ?>
                    <tr>
                        <th scope="row"><?= $action['action_id'] ?></th>
                        <td><?= htmlspecialchars($action['action']) ?></td>
                        <td><?= htmlspecialchars($action['datetime_stamp']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
