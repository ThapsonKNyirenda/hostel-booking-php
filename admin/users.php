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

// Fetch registration details
$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql); // Bind the logged-in user ID to the query
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    .sidebar {
        transition: transform 0.3s ease-in-out;
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    </style>
</head>

<body>
    <div class="flex h-screen bg-gray-100">

        <!-- Sidebar -->
        <div id="sidebar"
            class="sidebar fixed inset-0 md:relative md:flex md:w-64 bg-gray-800 transform -translate-x-full md:translate-x-0">

            <div class="flex flex-col flex-1 overflow-y-auto">
                <div class="flex items-center justify-between h-16 bg-gray-900 px-4">
                    <span class="text-white font-bold uppercase"></span>
                    <!-- Close Button for Sidebar (hidden on md and larger screens) -->
                    <button id="close-button" class="text-gray-400 hover:text-white focus:outline-none md:hidden">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <nav class="flex-1 px-2 py-4 bg-gray-800">
                    <a href="admin-home.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">
                        <i class="fas fa-tachometer-alt fa-lg mr-2"></i>
                        Dashboard
                    </a>
                    <a href="applications.php" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <i class="fas fa-clipboard-list fa-lg mr-2"></i>
                        Applications
                    </a>
                    <a href="users.php" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <i class="fas fa-users fa-lg mr-2"></i>
                        Users
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <i class="fas fa-user fa-lg mr-2"></i>
                        Profile
                    </a>
                    <a href="../logout.php" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <i class="fas fa-sign-out-alt fa-lg mr-2"></i>
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
                        class="md:hidden text-gray-500 focus:outline-none focus:text-gray-700 ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <input class="mx-4 w-full border rounded-md px-4 py-2" type="text" placeholder="Search">
                </div>
                <div class="flex items-center pr-4">
                    <button
                        class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l-7-7 7-7m5 14l7-7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-4">

                <!-- Card -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-xl font-semibold text-gray-800">Registered Users</h3>
                    <p class="text-gray-600 mt-2">Below is the list of registered users</p>

                    <!-- Table -->
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead>
                                <tr class="w-full bg-gray-100 border-b">
                                    <th class="py-2 px-4 text-left">#</th>
                                    <th class="py-2 px-4 text-left">Email</th>
                                    <th class="py-2 px-4 text-left">User Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                 $count = 1;
                                 
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='py-2 px-4 border-b'>" . $count. "</td>";
                                        echo "<td class='py-2 px-4 border-b'>" . htmlspecialchars($row['email']) . "</td>";
                                        echo "<td class='py-2 px-4 border-b'>" . htmlspecialchars($row['userType']) . "</td>";
                                        echo "</tr>";

                                        $count++;
                                    }
                                } else {
                                    echo "<tr><td colspan='10' class='py-2 px-4 text-center'>No records found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
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
    </script>
</body>

</html>