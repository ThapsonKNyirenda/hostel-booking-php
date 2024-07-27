<?php
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['user_id']) || isset($_SESSION['user_type'])) {
    // If not logged in, destroy the session and redirect to the index page
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}else{
    echo "User Not logged in";
    exit();
}
?>