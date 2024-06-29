<?php
include_once "./dbConnection.php";

$error = '';
$success = '';

if (isset($_POST["submit"])) 
{
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];


    if ($password !== $confirmPassword) {
        $error = "Passwords do not match";
    } else {
        // Hash the password for security purposes
        $hashed_password = md5($password);

        // Joining the first and last names
        $userName = $firstName . " " . $lastName;

        // Checking if a user with the same email exists
        $sql = "SELECT * FROM user WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) 
        {
            $error = "Email already exists";
        } 
        else 
        {
            // Insert the user into the database
            $sql = "INSERT INTO user (name, email, phone, password) VALUES ('$userName', '$email', '$phone', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                $success = "Registration successful. Redirecting to login page...";
                // Delay redirection for 2 seconds
                header("refresh:2;url=login.php");
            } else {
                $error = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .register-form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }

        .login-link {
            text-align: center;
            margin-top: 10px;
        }

        .login-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <?php
            if (!empty($error)) {
                echo "<div class='error-message'>" . $error . "</div>";
            } elseif (!empty($success)) {
                echo "<div class='success-message'>" . $success . "</div>";
            }
        ?>
        <form action="./register.php" method="post" class="register-form">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>
            
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>
            
            <button type="submit" name="submit">Register</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.html">Login here</a></p>
        </div>
    </div>
</body>
</html>
