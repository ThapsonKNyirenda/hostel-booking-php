<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, destroy the session and redirect to the index page
    session_unset();
    session_destroy();
    header("Location: ../index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Database configuration
include '../connection/dbconnection.php';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method']; // Get the payment method
    $proof = $_FILES['proof'];

    // Handle file upload
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($proof["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate the file is an image
    $check = getimagesize($proof["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // Move uploaded file to the uploads folder
    if (!move_uploaded_file($proof["tmp_name"], $target_file)) {
        die("Sorry, there was an error uploading your file.");
    }

    // Define variables for binding
    $full_fees = 60000;
    $verification_status = "pending";
    $current_date = date('Y-m-d H:i:s'); // current date and time

    // Insert payment into database
   // Insert payment into database
$stmt = $conn->prepare("INSERT INTO payments (user_id, amount, payment_method, proof, full_fees, verification_status, date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("isssss", $user_id, $amount, $payment_method, $target_file, $full_fees, $verification_status);


    if ($stmt->execute()) {
        // Update payment_status in applications table
        $update_stmt = $conn->prepare("UPDATE applications SET payment_status = 'paid' WHERE user_id = ?");
        $update_stmt->bind_param("i", $user_id);

        if ($update_stmt->execute()) {
            // If both the payment insertion and status update are successful, redirect to the payment page
            header("Location: payment.php?add_payment=success");
            exit();
        } else {
            // If updating the payment status fails, redirect back with a failure message
            header("Location: add_payment.php?failed=status_update_failed");
            exit();
        }

        $update_stmt->close();
    } else {
        // If inserting the payment fails, redirect back with a failure message
        header("Location: add_payment.php?failed=failed");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>