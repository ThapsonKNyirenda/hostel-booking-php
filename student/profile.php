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
include '../connection/dbconnection.php' ;
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$sql = "SELECT id, email, userType FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
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
            <div class="p-6 m-4 bg-white rounded-lg shadow-md">
                <h2 class="mb-4 text-2xl font-semibold text-gray-800">Profile</h2>
                <div class="flex flex-col items-center">
                    <!-- Profile Picture -->
                    <img src="img/profile_pic.jpg" alt="Profile Picture" class="w-32 h-32 mb-4 rounded-full">

                    <div class="text-center">
                        <p class="mb-2 text-lg font-medium text-gray-700">ID: <?php echo htmlspecialchars($user['id']); ?></p>
                        <p class="mb-2 text-lg font-medium text-gray-700">Email: <?php echo htmlspecialchars($user['email']); ?></p>
                        <p class="mb-2 text-lg font-medium text-gray-700">User Type: <?php echo htmlspecialchars($user['userType']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('menu-button').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('-translate-x-full');
    });

    document.getElementById('close-button').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.add('-translate-x-full');
    });

    function showNotification(message) {
        const notification = document.createElement("div");
        notification.className = "notification"; // Apply the notification class
        notification.textContent = message;
        document.body.appendChild(notification);

        // Trigger animation
        setTimeout(() => {
            notification.classList.add("show");
        }, 100);

        // Hide after 3 seconds
        setTimeout(() => {
            notification.classList.remove("show");
            notification.classList.add("hide");
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }

    // Check for the query parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('message') === 'success') {
        showNotification('Registration successful!');
        urlParams.get('message') = '';
    } else if (urlParams.get('message') === 'already_exist') {
        showNotification('You have already submitted the registration form!');
        urlParams.get('message') = '';
    }
    </script>
</body>

</html>

<?php
$conn->close(); // Close the database connection
?>