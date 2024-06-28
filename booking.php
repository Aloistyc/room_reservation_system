<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "Room";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO bookings (firstName, lastName, email, phone, checkin, checkout, roomType, paymentMethod) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $firstName, $lastName, $email, $phone, $checkin, $checkout, $roomType, $paymentMethod);

    // Set parameters and execute
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $checkin = $_POST["checkin"];
    $checkout = $_POST["checkout"];
    $roomType = $_POST["roomType"];
    $paymentMethod = $_POST["paymentMethod"];
    
    // Check if room type is fully booked
    $availableRooms = getAvailableRooms($conn, $roomType);
    if ($availableRooms > 0) {
        $stmt->execute();
        echo "<p>Booking successful!</p>";
    } else {
        echo "<p>Sorry, all rooms of type $roomType are fully booked. Please try another room type.</p>";
    }

    $stmt->close();
    $conn->close();
}

function getAvailableRooms($conn, $roomType) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM bookings WHERE roomType = ?");
    $stmt->bind_param("s", $roomType);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $bookedRooms = $row["count"];
    $totalRooms = getTotalRooms($roomType);
    $availableRooms = $totalRooms - $bookedRooms;
    return $availableRooms;
}

function getTotalRooms($roomType) {
    switch ($roomType) {
        case "Single room":
            return 10;
        case "Deluxe room":
            return 8;
        case "Twin room":
            return 6;
        case "Suite room":
            return 3;
        case "Family room":
            return 7;
        default:
            return 0;
    }
}
?>
