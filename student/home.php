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
$sql = "SELECT * FROM applications WHERE user_id = ?";
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
                    <span class="font-bold text-white uppercase"></span>
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

                </div>
            </div>

            <!-- Content Section -->
            <div class="p-4">
                <!-- Button before the card -->
                <a href="register.php"
                    class="inline-block px-4 py-2 mb-4 text-white bg-gray-800 rounded-md hover:bg-gray-900 focus:outline-none">
                    Apply Now
                </a>
                <!-- Card -->
                <div class="p-4 bg-white rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800">Registration Details</h3>
                    <p class="mt-2 text-gray-600">Below are your registered details:</p>

                    <!-- Table -->
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead>
                                <tr class="w-full bg-gray-100 border-b">
                                    <th class="px-4 py-2 text-left">First Name</th>
                                    <th class="px-4 py-2 text-left">Last Name</th>
                                    <th class="px-4 py-2 text-left">Phone Number</th>
                                    <th class="px-4 py-2 text-left">Gender</th>
                                    <th class="px-4 py-2 text-left">Registration Date</th>
                                    <th class="px-4 py-2 text-left">Room Number</th>
                                    <th class="px-4 py-2 text-left">Home Address</th>
                                    <th class="px-4 py-2 text-left">Course</th>
                                    <th class="px-4 py-2 text-left">Next of Kin Name</th>
                                    <th class="px-4 py-2 text-left">Next of Kin Address</th>
                                    <th class="px-4 py-2 text-left">Next of Kin Phone Number</th>
                                    <th class="px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='px-4 py-2 border-b'>" . htmlspecialchars($row['first_name']) . "</td>";
                                        echo "<td class='px-4 py-2 border-b'>" . htmlspecialchars($row['last_name']) . "</td>";
                                        echo "<td class='px-4 py-2 border-b'>" . htmlspecialchars($row['phone_number']) . "</td>";
                                        echo "<td class='px-4 py-2 border-b'>" . htmlspecialchars($row['gender']) . "</td>";
                                        echo "<td class='px-4 py-2 border-b'>" . htmlspecialchars($row['registration_date']) . "</td>";
                                        echo "<td class='px-4 py-2 border-b'>" . $row['room_number'] . "</td>";
                                        echo "<td class='px-4 py-2 border-b'>" . htmlspecialchars($row['home_address']) . "</td>";
                                        echo "<td class='px-4 py-2 border-b'>" . htmlspecialchars($row['course']) . "</td>";
                                        echo "<td class='px-4 py-2 border-b'>" . htmlspecialchars($row['next_of_kin_name']) . "</td>";
                                        echo "<td class='px-4 py-2 border-b'>" . htmlspecialchars($row['next_of_kin_current_address']) . "</td>";
                                        echo "<td class='px-4 py-2 border-b'>" . htmlspecialchars($row['next_of_kin_phone_number']) . "</td>";
                                        
                                        // Edit Icon Column
                                        echo "<td class='px-4 py-2 text-center border-b'>
        <a href='edit_form.php?id=" . $row['id'] . "' class='text-gray-500 hover:text-gray-700'>
            <img src='img/edit.svg' alt='Edit' class='w-6 h-6'>
        </a>
      </td>";
echo "</tr>";

                                    }
                                } else {
                                    echo "<tr><td colspan='12' class='px-4 py-2 text-center'>No records found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="notification hide">
        Operation Successfully!
    </div>

    <div id="notification-already-exist" class="notification hide">
        You have already applied, Please you can now only edit!
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

    function showAlreadyExistNotification() {
        const notification = document.getElementById('notification-already-exist');
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
    } else if (urlParams.has('updated')) {
        showNotification();
    } else if (urlParams.has('message')) {
        showAlreadyExistNotification();
    }
    </script>
</body>

</html>