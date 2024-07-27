<?php
include 'connection/dbconnection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Validate passwords
    if ($password !== $confirm_password) {
        header("Location: signup.html?signup=pass_mismatch");
            exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (email, password, userType) VALUES (?, ?, 'user')");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        header("Location: signup.html?signup=success");
        exit();
    } else {
        header("Location: signup.html?signup=failed");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>