<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php"); // Redirect to admin login page if not logged in
    exit();
}

// // Database connection details
// $servername = "localhost";
// $username = "root";
// $password = ""; 
// $dbname = "Room";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Function to get the number of booked rooms for each room type
function getBookedRoomsCount($conn, $roomType) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM bookings WHERE roomType = ?");
    $stmt->bind_param("s", $roomType);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row["count"];
}

// Function to get the status of each room type
function getRoomStatus($conn, $roomType, $totalRooms) {
    $bookedRooms = getBookedRoomsCount($conn, $roomType);
    $availableRooms = $totalRooms - $bookedRooms;
    return $availableRooms;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        .dashboard-section {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            max-width: 400px;
            margin-top: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type=text], input[type=email], input[type=date], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <!-- Section to show status of each room type -->
    <div class="dashboard-section">
        <h2>Room Status</h2>
        <table>
            <tr>
                <th>Room Type</th>
                <th>Available Rooms</th>
            </tr>
            <tr>
                <td>Single room</td>
                <td><?php echo getRoomStatus($conn, 'Single room', 10); ?></td>
            </tr>
            <tr>
                <td>Deluxe room</td>
                <td><?php echo getRoomStatus($conn, 'Deluxe room', 8); ?></td>
            </tr>
            <tr>
                <td>Twin room</td>
                <td><?php echo getRoomStatus($conn, 'Twin room', 6); ?></td>
            </tr>
            <tr>
                <td>Suite room</td>
                <td><?php echo getRoomStatus($conn, 'Suite room', 3); ?></td>
            </tr>
            <tr>
                <td>Family room</td>
                <td><?php echo getRoomStatus($conn, 'Family room', 7); ?></td>
            </tr>
        </table>
    </div>

    <!-- Section to show number of rooms booked per room type -->
    <div class="dashboard-section">
        <h2>Booked Rooms</h2>
        <table>
            <tr>
                <th>Room Type</th>
                <th>Booked Rooms</th>
            </tr>
            <tr>
                <td>Single room</td>
                <td><?php echo getBookedRoomsCount($conn, 'Single room'); ?></td>
            </tr>
            <tr>
                <td>Deluxe room</td>
                <td><?php echo getBookedRoomsCount($conn, 'Deluxe room'); ?></td>
            </tr>
            <tr>
                <td>Twin room</td>
                <td><?php echo getBookedRoomsCount($conn, 'Twin room'); ?></td>
            </tr>
            <tr>
                <td>Suite room</td>
                <td><?php echo getBookedRoomsCount($conn, 'Suite room'); ?></td>
            </tr>
            <tr>
                <td>Family room</td>
                <td><?php echo getBookedRoomsCount($conn, 'Family room'); ?></td>
            </tr>
        </table>
    </div>

    <!-- Section to book a room for a client -->
    <div class="dashboard-section">
        <h2>Book a Room</h2>
        <form action="book_room.php" method="post">
            <label for="clientName">Client Name:</label>
            <input type="text" id="clientName" name="clientName" required>
            
            <label for="clientEmail">Client Email:</label>
            <input type="email" id="clientEmail" name="clientEmail" required>
            
            <label for="checkinDate">Check-in Date:</label>
            <input type="date" id="checkinDate" name="checkinDate" required>
            
            <label for="checkoutDate">Check-out Date:</label>
            <input type="date" id="checkoutDate" name="checkoutDate" required>
            
            <label for="roomType">Room Type:</label>
            <select id="roomType" name="roomType" required>
                <option value="Single room">Single room</option>
                <option value="Deluxe room">Deluxe room</option>
                <option value="Twin room">Twin room</option>
                <option value="Suite room">Suite room</option>
                <option value="Family room">Family room</option>
            </select>
            
            <button type="submit">Book Room</button>
        </form>
    </div>

    <a href="admin_logout.php">Logout</a>

</body>
</html>
