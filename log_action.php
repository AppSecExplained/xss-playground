<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // Insert the action into the database
    $stmt = $conn->prepare("INSERT INTO actions (action, datetime_stamp) VALUES (?, NOW())");
    $stmt->bind_param("s", $action);
    $stmt->execute();
    $stmt->close();
}
