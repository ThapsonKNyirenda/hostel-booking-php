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
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch registration details
$sql = "SELECT * FROM applications WHERE owner = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id); // Bind the logged-in user ID to the query
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    .sidebar {
        transition: transform 0.3s ease-in-out;
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    .notification {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        opacity: 1;
        transition: opacity 0.5s ease, transform 0.5s ease;
        transform: translateX(-50%) translateY(-20px);
    }

    .notification.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    .notification.hide {
        opacity: 0;
        transform: translateX(-50%) translateY(-20px);
    }
    </style>
</head>

<body>
    <div class="flex h-screen bg-gray-100">

        <!-- sidebar -->
        <div id="sidebar"
            class="fixed inset-0 transform -translate-x-full bg-gray-800 sidebar md:relative md:flex md:w-64 md:translate-x-0">

            <div class="flex flex-col flex-1 overflow-y-auto">
                <div class="flex items-center justify-between h-16 px-4 bg-gray-900">
                    <span class="font-bold text-white uppercase">Sidebar</span>
                    <!-- Close Button for Sidebar (hidden on md and larger screens) -->
                    <button id="close-button" class="text-gray-400 hover:text-white focus:outline-none md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="flex-1 px-2 py-4 bg-gray-800">
                    <a href="home.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        Applications
                    </a>
                    <a href="payment.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        Payments
                    </a>
                    <a href="profile.php" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Profile
                    </a>
                    <a href="../logout.php" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Logout
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-y-auto">
            <div class="flex items-center justify-between h-16 bg-white border-b border-gray-200">
                <div class="flex items-center">
                    <!-- Hamburger Menu for Mobile -->
                    <button id="menu-button"
                        class="ml-4 text-gray-500 md:hidden focus:outline-none focus:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <input class="w-full px-4 py-2 mx-4 border rounded-md" type="text" placeholder="Search">
                </div>
                <div class="flex items-center pr-4">
                    <button
                        class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l-7-7 7-7m5 14l7-7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content Section -->
            <div class="container p-4 mx-auto">
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h2 class="mb-6 text-2xl font-bold">Edit Registration Details</h2>
                    <form action="update_registration.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="first_name">First Name</label>
                            <input
                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="first_name" name="first_name" type="text" placeholder="First Name"
                                value="<?php echo htmlspecialchars($data['first_name']); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="last_name">Last Name</label>
                            <input
                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="last_name" name="last_name" type="text" placeholder="Last Name"
                                value="<?php echo htmlspecialchars($data['last_name']); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="phone_number">Phone Number</label>
                            <input
                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="phone_number" name="phone_number" type="text" placeholder="Phone Number"
                                value="<?php echo htmlspecialchars($data['phone_number']); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="gender">Gender</label>
                            <div class="flex items-center">
                                <input class="mr-2 leading-tight" type="radio" id="male" name="gender" value="male"
                                    <?php echo ($data['gender'] == 'male') ? 'checked' : ''; ?> required>
                                <label class="text-gray-700" for="male">Male</label>
                                <input class="ml-4 mr-2 leading-tight" type="radio" id="female" name="gender"
                                    value="female" <?php echo ($data['gender'] == 'female') ? 'checked' : ''; ?>
                                    required>
                                <label class="text-gray-700" for="female">Female</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="registration_date">Registration
                                Date</label>
                            <input
                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="registration_date" name="registration_date" type="date"
                                value="<?php echo htmlspecialchars($data['registration_date']); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="home_address">Home Address</label>
                            <textarea
                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="home_address" name="home_address" rows="3" placeholder="Home Address"
                                required><?php echo htmlspecialchars($data['home_address']); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="course">Course being pursued</label>
                            <input
                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="course" name="course" type="text" placeholder="Course"
                                value="<?php echo htmlspecialchars($data['course']); ?>" required>
                        </div>

                        <!-- Guardian/Next of Kin Information -->
                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="next_of_kin_name">Next of Kin
                                Name</label>
                            <input
                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="next_of_kin_name" name="next_of_kin_name" type="text" placeholder="Next of Kin Name"
                                value="<?php echo htmlspecialchars($data['next_of_kin_name']); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="address">Next of kin Address</label>
                            <textarea
                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="address" name="address" placeholder="Your Address"
                                required><?php echo htmlspecialchars($data['course']); ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="next_of_kin_phone">Next of Kin Phone
                                Numbers</label>
                            <input
                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="next_of_kin_phone" name="next_of_kin_phone" type="text"
                                placeholder="Next of Kin Phone Numbers"
                                value="<?php echo htmlspecialchars($data['next_of_kin_phone_number']); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="course">Room Number</label>
                            <input
                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="room_number" name="room_number" type="text" placeholder="room Number"
                                value="<?php echo htmlspecialchars($data['room_number']); ?>" required>
                        </div>

                        <div class="flex items-center">
                            <button type="submit"
                                class="px-4 py-2 font-bold text-white bg-gray-800 rounded hover:bg-gray-900 focus:outline-none focus:shadow-outline">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="notification hide">
        Application Submitted Successfully!
    </div>

    <!-- Script to toggle the sidebar -->
    <script>
    document.getElementById('menu-button').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
    });

    document.getElementById('close-button').addEventListener('click', function() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
    });

    // Show notification function
    function showNotification() {
        const notification = document.getElementById('notification');
        notification.classList.remove('hide');
        notification.classList.add('show');

        // Hide the notification after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            notification.classList.add('hide');
        }, 3000);
    }

    // Check if the 'submitted' parameter is present in the URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('submitted')) {
        showNotification();
    }
    </script>
</body>

</html>