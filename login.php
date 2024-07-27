<?php
session_start(); // Start the session

// Database connection
include 'connection/dbconnection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Query to check user credentials
    $sql = "SELECT id, password, userType FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['userType'];

            // Redirect based on user type
            if ($user['userType'] == 'admin') {
                header("Location: admin/admin-home.php");
            } else if ($user['userType'] == 'user') {
                header("Location: student/home.php");
            } else {
                // Handle unexpected user types
                header("Location: index.html?login=failed");
            exit();
            }
            exit();
        } else {
            // Invalid password
            header("Location: index.html?login=failed");
            exit();
        }
    } else {
        // No user found with that email
        header("Location: index.html?login=failed");
            exit();
    }
}

$conn->close();
?>