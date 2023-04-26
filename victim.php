<?php
require 'db.php';

if (!isset($_GET['payload_id'])) {
    header('Location: payloads.php');
    exit;
}

$payload_id = $_GET['payload_id'];

// Fetch the payload from the database
$stmt = $conn->prepare("SELECT payload FROM payloads WHERE payload_id = ?");
$stmt->bind_param("i", $payload_id);
$stmt->execute();
$result = $stmt->get_result();
$payload = $result->fetch_assoc()['payload'];
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Payload Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function logAction(action) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'log_action.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('action=' + encodeURIComponent(action));
        }
    </script>
</head>

<body>
    <div class="container">
        <h1 class="my-4">XSS Payload Demo - Victim Page</h1>
        <?= $payload ?>

        <h2 class="my-4">Login Form</h2>
        <form onsubmit="event.preventDefault(); logAction('Login attempted');" class="mb-3">
            <input type="text" class="form-control mb-2" placeholder="Username" required>
            <input type="password" class="form-control mb-2" placeholder="Password" required>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <h2 class="my-4">Text Input Form</h2>
        <form onsubmit="event.preventDefault(); logAction('Text input submitted');" class="mb-3">
            <textarea rows="5" class="form-control mb-2" placeholder="Enter text here" required></textarea>
            <button type="submit" class="btn btn-primary">Submit Text</button>
        </form>

        <h2 class="my-4">Update Email Form</h2>
        <form onsubmit="event.preventDefault(); logAction('Email updated!');" class="mb-3">
            <input type="email" class="form-control mb-2" placeholder="New Email" required>
            <button type="submit" class="btn btn-primary">Update Email</button>
        </form>

    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var usernameInput = document.querySelector('input[type="text"][placeholder="Username"]');
                var passwordInput = document.querySelector('input[type="password"][placeholder="Password"]');
                
                if (usernameInput && passwordInput) {
                    usernameInput.value = 'autofillusername';
                    passwordInput.value = 'autofillpassword';
                }
            }, 1000);
        });
</script>
</body>

</html>
