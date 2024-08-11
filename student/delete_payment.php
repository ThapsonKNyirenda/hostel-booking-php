<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the payment ID is set
if (isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];

    // Database configuration
    include '../connection/dbconnection.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete the payment from the database
    $stmt = $conn->prepare("DELETE FROM payments WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $payment_id, $user_id);

    if ($stmt->execute()) {
        header("Location: payment.php?delete=success");
    } else {
        header("Location: payment.php?delete=failed");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: payment.php?delete=error");
}
?>