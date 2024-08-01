<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
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
    // Fetch other fields similarly...

    // Database configuration
    include '../connection/dbconnection.php';
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the registration details in the database
    $sql = "UPDATE applications SET first_name=?, last_name=?, phone_number=?, gender=?, registration_date=?, home_address=?, course=?, next_of_kin_name=?, next_of_kin_current_address=?, next_of_kin_phone_number=?, room_number=?  WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssi", $first_name, $last_name, $phone_number, $gender, $registration_date, $home_address, $course, $next_of_kin_name, $next_of_kin_address, $next_of_kin_phone, $room_number, $id);
    // Bind other parameters similarly...
    
    if ($stmt->execute()) {
        header("Location: home.php?updated=true");
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>