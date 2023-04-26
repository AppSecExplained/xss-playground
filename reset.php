<?php
require 'db.php';

// Clear out the current database
$conn->query("DROP TABLE IF EXISTS payloads");
$conn->query("DROP TABLE IF EXISTS actions");

// Create the actions table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS actions (
    action_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    action TEXT NOT NULL,
    datetime_stamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)");

// Create the payloads table
$conn->query("CREATE TABLE payloads (
    payload_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payload TEXT NOT NULL,
    description VARCHAR(255) NOT NULL
)");

// Populate the payloads table with test data
$payloads = [
    [
        'payload' => '<script>prompt("add something more exciting")</script>',
        'description' => 'Basic XSS.'
    ],
    [
        'payload' => '<img src="nonexistent" onerror="alert(\'add something more exciting\');" />',
        'description' => 'Basic XSS with onerror event.'
    ],
];

foreach ($payloads as $payload) {
    $stmt = $conn->prepare("INSERT INTO payloads (payload, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $payload['payload'], $payload['description']);
    $stmt->execute();
    $stmt->close();
}

// Insert a test action
$test_action = "Test action";
$stmt = $conn->prepare("INSERT INTO actions (action) VALUES (?)");
$stmt->bind_param("s", $test_action);
$stmt->execute();
$stmt->close();

echo "<p>Database reset and test data inserted successfully.</p>";
echo "<a href='index.php'>Back to index.php</a>"; 

$conn->close();
