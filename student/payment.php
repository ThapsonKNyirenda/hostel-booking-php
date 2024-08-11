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

// Fetch payment details
$sql = "SELECT * FROM payments WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind the logged-in user ID to the query
$stmt->execute();
$result = $stmt->get_result();

// Check if the user has an entry in the applications table
$app_check_sql = "SELECT COUNT(*) FROM applications WHERE user_id = ?";
$app_stmt = $conn->prepare($app_check_sql);
$app_stmt->bind_param("i", $user_id);
$app_stmt->execute();
$app_stmt->bind_result($app_count);
$app_stmt->fetch();
$app_stmt->close();

$has_application = $app_count > 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                        <i class="fa-solid fa-money-bill"></i>
                        &nbsp; Payments
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
                </div>
            </div>

            <!-- Content Section -->
            <div class="container p-4 mx-auto">
                <!-- Search and Filter -->
                <div class="flex justify-between mb-4">
                    <div class="flex">
                        <input type="text" placeholder="Search" class="w-full max-w-xs px-4 py-2 border rounded-md">
                        <select class="ml-4 px-4 py-2 border rounded-md">
                            <option value="">Filter by Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                        </select>
                    </div>
                    <!-- Add Payment Button -->
                    <?php if ($has_application): ?>
                    <a href="add_payment.php"><button
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                            Add Payment
                        </button></a>
                    <?php else: ?>
                    <button class="px-4 py-2 text-sm font-medium text-white bg-gray-500 rounded-md cursor-not-allowed"
                        disabled>
                        Add Payment
                    </button>
                    <p class="text-red-500 text-sm mt-2">Please apply first</p>
                    <?php endif; ?>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">Paid Amount
                                </th>
                                <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">Balance</th>
                                <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">Verification
                                    Status</th>
                                <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo date('Y-m-d H:i:s', strtotime($row['date'])); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700"><?php echo $row['amount']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo $row['full_fees'] - $row['amount']; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700"><?php echo $row['verification_status']; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <form method="POST" action="delete_payment.php">
                                        <input type="hidden" name="payment_id" value="<?php echo $row['id']; ?>" />
                                        <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                </td>

                            </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
</body>
<script>
document.getElementById('menu-button').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
});

document.getElementById('close-button').addEventListener('click', function() {
    document.getElementById('sidebar').classList.add('-translate-x-full');
});
</script>

</html>