<?php
include_once "./dbConnection.php";

$error = '';

if (isset($_POST["submit"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Checking if the credentials are correct
    $userQuery = "SELECT * FROM user WHERE email='$email'";
    $result = mysqli_query($conn, $userQuery);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        $error = "User not found";
    } elseif ($row && md5($password) == $row["password"] && $email == $row["email"]) {
        session_start();
        $_SESSION["admin_logged_in"] = true;
        header("Location: index.html");
        exit();
    } else {
        $error = "Invalid login credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .login-container {
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

        .login-form {
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

        .register-link {
            text-align: center;
            margin-top: 10px;
        }

        .register-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const errorMessage = document.querySelector('.error-message');
            if (errorMessage) 
            {
                setTimeout(() => 
                {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
            if (!empty($error)) 
            {
                echo "<div class='error-message'>" . $error . "</div>";
            }
        ?>
        <form action="./login.php" method="post" class="login-form">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit" name="submit">Login</button>
        </form>
        <div class="register-link">
            <p>Don't have an account? <a href="./register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
