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
        header("Location: signup.html?failed=pass_mismatch");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: signup.html?failed=email exists");
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
        header("Location: signup.html?failed=failed");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>