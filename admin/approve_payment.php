<?php
session_start(); // Start the session

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    // If not logged in or not an admin, destroy the session and redirect to the index page
    session_unset();
    session_destroy();
    header("Location: ../index.html");
    exit();
}

// Database configuration
include '../connection/dbconnection.php';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_id = $_POST['payment_id'];

    // Update verification_status to 'approved'
    $stmt = $conn->prepare("UPDATE payments SET verification_status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $payment_id);

    if ($stmt->execute()) {
        // Redirect to the payments page with a success message
        header("Location: admin_payment.php?status=approved");
        exit();
    } else {
        // Redirect to the payments page with a failure message
        header("Location: admin_payment.php?status=failed");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>