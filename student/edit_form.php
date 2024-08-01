<?php
// Fetch the data for the provided ID and populate the form
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Database configuration
    include '../connection/dbconnection.php';
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the existing data for the given ID
    $sql = "SELECT * FROM applications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
} else {
    die("ID not provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Registration Details</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container p-4 mx-auto">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h2 class="mb-6 text-2xl font-bold">Edit Registration Details</h2>
            <form action="update_registration.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                <!-- Include input fields similar to the registration form, populated with existing data -->
                <div class="mb-4">
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($data['first_name']); ?>" required>
                </div>
                <div class="mb-4">
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($data['last_name']); ?>" required>
                </div>
                <!-- Add more input fields as necessary -->

                <div class="flex items-center">
                    <button type="submit" class="px-4 py-2 font-bold text-white bg-gray-800 rounded hover:bg-gray-900 focus:outline-none focus:shadow-outline">Update</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
