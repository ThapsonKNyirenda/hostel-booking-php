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

// Fetch total users
$totalUsersResult = $conn->query("SELECT COUNT(*) as count FROM users");
$totalUsers = $totalUsersResult->fetch_assoc()['count'];

// Fetch students
$studentsResult = $conn->query("SELECT COUNT(*) as count FROM users WHERE userType = 'user'");
$students = $studentsResult->fetch_assoc()['count'];

// Fetch admins
$adminsResult = $conn->query("SELECT COUNT(*) as count FROM users WHERE userType = 'admin'");
$admins = $adminsResult->fetch_assoc()['count'];

// Fetch applications
$applicationsResult = $conn->query("SELECT COUNT(*) as count FROM applications");
$applications = $applicationsResult->fetch_assoc()['count'];

// Close connection
$conn->close();


?>
<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
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
            class="fixed inset-0 transform -translate-x-full bg-gray-800 sidebar md:relative md:flex md:w-64 md:translate-x-0">

            <div class="flex flex-col flex-1 overflow-y-auto">
                <div class="flex items-center justify-between h-16 px-4 bg-gray-900">
                    <span class="font-bold text-white uppercase"></span>
                    <!-- Close Button for Sidebar (hidden on md and larger screens) -->
                    <button id="close-button" class="text-gray-400 hover:text-white focus:outline-none md:hidden">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <nav class="flex-1 px-2 py-4 bg-gray-800">
                    <a href="admin-home.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">
                        <i class="mr-2 fas fa-tachometer-alt fa-lg"></i>
                        Dashboard
                    </a>
                    <a href="applications.php" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <i class="mr-2 fas fa-clipboard-list fa-lg"></i>
                        Applications
                    </a>
                    <a href="users.php" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <i class="mr-2 fas fa-users fa-lg"></i>
                        Users
                    </a>
                    <a href="profile.php" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <i class="mr-2 fas fa-user fa-lg"></i>
                        Profile
                    </a>
                    <a href="../logout.php" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <i class="mr-2 fas fa-sign-out-alt fa-lg"></i>
                        Logout
                    </a>
                </nav>
            </div>
        </div>


        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-y-auto">
            <div class="flex items-center justify-between h-16 bg-white border-b border-gray-200">
                <div class="flex items-center px-4">
                    <!-- Hamburger Menu for Mobile -->
                    <button id="menu-button" class="text-gray-500 md:hidden focus:outline-none focus:text-gray-700">
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
            <div class="p-4">
                <!-- <h1 class="text-2xl font-bold">Dashboard Overview</h1> -->

                <!-- Dashboard Cards -->
                <div class="grid grid-cols-1 gap-4 mt-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <h2 class="text-xl font-bold text-gray-700">Total Users</h2>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">
                            <?php echo ($totalUsers);?>
                        </p>
                    </div>
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <h2 class="text-xl font-bold text-gray-700">Students</h2>
                        <p class="mt-2 text-3xl font-semibold text-gray-900"><?php echo ($students);?></p>
                    </div>
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <h2 class="text-xl font-bold text-gray-700">Admins</h2>
                        <p class="mt-2 text-3xl font-semibold text-gray-900"><?php echo ($admins);?></p>
                    </div>
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <h2 class="text-xl font-bold text-gray-700">Applications</h2>
                        <p class="mt-2 text-3xl font-semibold text-gray-900"><?php echo ($applications);?></p>
                    </div>
                </div>

                <!-- Sample Graphs -->
                <div class="flex flex-col mt-6 md:flex-row md:space-x-4">
                    <div class="flex-1 p-4 bg-white rounded-lg shadow-md">
                        <h2 class="mb-4 text-xl font-bold text-gray-700">User Growth</h2>
                        <div class="chart-container">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>

                    <div class="flex-1 p-4 mt-6 bg-white rounded-lg shadow-md md:mt-0">
                        <h2 class="mb-4 text-xl font-bold text-gray-700">Applications by Type</h2>
                        <div class="chart-container">
                            <canvas id="applicationsChart"></canvas>
                        </div>
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

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'User Growth',
                data: [50, 60, 70, 90, 110, 130],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

    // Applications by Type Chart
    const applicationsCtx = document.getElementById('applicationsChart').getContext('2d');
    new Chart(applicationsCtx, {
        type: 'pie',
        data: {
            labels: ['Type A', 'Type B', 'Type C', 'Type D'],
            datasets: [{
                label: 'Applications by Type',
                data: [300, 150, 100, 50],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
    </script>
</body>

</html>