<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    // Fetch other fields similarly...

    // Database configuration
    include '../connection/dbconnection.php';
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the registration details in the database
    $sql = "UPDATE applications SET first_name = ?, last_name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $first_name, $last_name, $id);
    // Bind other parameters similarly...
    
    if ($stmt->execute()) {
        header("Location: home.php?submitted=true");
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>