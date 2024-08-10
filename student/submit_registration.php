<?php

session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    // If not logged in, destroy the session and redirect to the index page
    session_unset();
    session_destroy();
    header("Location: ../index.html");
    exit();
}
$user_id = $_SESSION['user_id']; 

// Database configuration
include '../connection/dbconnection.php';

// Collect form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$phone_number = $_POST['phone_number'];
$gender = $_POST['gender'];
$registration_date = $_POST['registration_date'];
$home_address = $_POST['home_address'];
$course = $_POST['course'];
$next_of_kin_name = $_POST['next_of_kin_name'];
$next_of_kin_address = $_POST['next_of_kin_address'];
$next_of_kin_phone = $_POST['next_of_kin_phone'];
$room_number = $_POST['room_number'];

// Check if a row with the same owner already exists
$check_stmt = $conn->prepare("SELECT * FROM applications WHERE owner = ?");
$check_stmt->bind_param("s", $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Row with the same owner already exists, show an error
    header("Location: home.php?message=already_exist");
    exit();
}

// Prepare SQL statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO applications (user_id,first_name, last_name, phone_number, gender, registration_date, home_address, course, next_of_kin_name, next_of_kin_current_address, next_of_kin_phone_number, owner, room_number,payment_status) VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssssssss", $user_id,$first_name, $last_name, $phone_number, $gender, $registration_date, $home_address, $course, $next_of_kin_name, $next_of_kin_address, $next_of_kin_phone, $user_id, $room_number,"pending Payment");

// Execute the statement
if ($stmt->execute()) {
    header("Location: home.php?message=success");
    exit();
} else {
    header("Location: home.php?message=failed");
    exit();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>