<?php
// Include your database connection
include '../connection/dbconnection.php';

// Get the JSON input from the request
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['appId']) && isset($input['newBalance'])) {
    $appId = $input['appId'];
    $newBalance = $input['newBalance'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE applications SET balance = balance - ? WHERE id = ?");
    $stmt->bind_param("di", $newBalance, $appId);

    if ($stmt->execute()) {
        // Success
        echo json_encode(["status" => "success", "message" => "Balance updated successfully."]);
    } else {
        // Failure
        echo json_encode(["status" => "error", "message" => "Error updating balance."]);
    }

    $stmt->close();
} else {
    // Missing parameters
    echo json_encode(["status" => "error", "message" => "Invalid parameters."]);
}

$conn->close();
?>