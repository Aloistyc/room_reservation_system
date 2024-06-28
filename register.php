<?php
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

/* Create table if not exists
$sqlCreateTable = "CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phoneNumber VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
)"; */

if ($conn->query($sqlCreateTable) === FALSE) {
    echo "Error creating table: " . $conn->error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Prepare and bind the INSERT statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, email, phoneNumber, password)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $phoneNumber, $password);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Registration successful";
    } else {
        echo "Error inserting user: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
