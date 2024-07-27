<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Occupant Registration Form</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Occupant Registration Form</h2>
            <form action="submit_registration.php" method="POST">
                <!-- Personal Details -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="first_name">First Name</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="first_name" name="first_name" type="text" placeholder="First Name" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="last_name">Last Name</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="last_name" name="last_name" type="text" placeholder="Last Name" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="phone_number">Phone Number</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="phone_number" name="phone_number" type="text" placeholder="Phone Number" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="gender">Gender</label>
                    <div class="flex items-center">
                        <input class="mr-2 leading-tight" type="radio" id="male" name="gender" value="male" required>
                        <label class="text-gray-700" for="male">Male</label>
                        <input class="mr-2 ml-4 leading-tight" type="radio" id="female" name="gender" value="female"
                            required>
                        <label class="text-gray-700" for="female">Female</label>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="registration_date">Registration Date</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="registration_date" name="registration_date" type="date" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="home_address">Home Address</label>
                    <textarea
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="home_address" name="home_address" rows="3" placeholder="Home Address" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="course">Course being pursued</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="course" name="course" type="text" placeholder="Course" required>
                </div>

                <!-- Guardian/Next of Kin Information -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="next_of_kin_name">Next of Kin Name</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="next_of_kin_name" name="next_of_kin_name" type="text" placeholder="Next of Kin Name"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="next_of_kin_address">Next of Kin Current
                        Address</label>
                    <textarea
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="next_of_kin_address" name="next_of_kin_address" rows="3"
                        placeholder="Next of Kin Current Address" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="next_of_kin_phone">Next of Kin Phone
                        Numbers</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="next_of_kin_phone" name="next_of_kin_phone" type="text"
                        placeholder="Next of Kin Phone Numbers" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="course">Room Number</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="room_number" name="room_number" type="text" placeholder="room Number" required>
                </div>

                <div class="flex items-center">
                    <a href="home.php"
                        class="bg-gray-300 mr-5 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancel
                    </a>
                    <button
                        class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>