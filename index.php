<?php
    require 'db.php';

    // check db
    $result = $conn->query("SHOW TABLES LIKE 'payloads'");
    $tableExists = $result->num_rows > 0;
    $result->close();

    if (!$tableExists) {
        echo "<div class='alert alert-warning'>DB needs to be initialized. Click <a href='reset.php'>reset.php</a> to initialize the database.</div>";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $payload = $_POST['payload'];
        $description = $_POST['description'];

        // Insert the payload and description into the database
        $stmt = $conn->prepare("INSERT INTO payloads (payload, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $payload, $description);
        $stmt->execute();
        $stmt->close();
    }

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
    <title>XSS Payload Demo</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="my-4">XSS Payload Demo</h1>

        <h2 class="my-4">Add a new payload</h2>

        <form method="POST" action="index.php">
            <div class="mb-3">
                <label for="payload" class="form-label">Payload</label>
                <textarea class="form-control" name="payload" id="payload" rows="5" placeholder="Enter your payload here"></textarea>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" name="description" id="description" placeholder="Enter the description here">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h2 class="my-4">Available XSS Payloads</h2>

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

        <h2 class="my-4">Action Log</h2>

        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Action</th>
                <th scope="col">Date and Time</th>
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
        <h2 class="my-4">Application actions</h2>
        <button onclick="setDemoCookie()" class="btn btn-primary">Set Demo Cookie</button>
        <a href="reset.php" class="btn btn-danger">Reset Database</a>
    </div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script>
    function setDemoCookie() {
        var cookieName = 'demoCookie';
        var cookieValue = 'thisIsTheUsersCookie';
        var expirationDays = 7; // Set the cookie to expire in 7 days
        var date = new Date();
        date.setTime(date.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
        var expires = 'expires=' + date.toUTCString();
        
        document.cookie = cookieName + '=' + cookieValue + ';' + expires + ';path=/';
    }
</script>
</body>
</html>
