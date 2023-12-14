<?php
// Include the database connection file here
include('includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username_or_email = htmlspecialchars($_POST['username_or_email']);
    $password = $_POST['password'];

    // Query the database to check for credentials
    $sql = "SELECT * FROM CUSTOMER WHERE CID = ? OR EMAIL = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['PASSWORD'])) {
            // Start session and store user ID
            session_start();
            $_SESSION['user_id'] = $row['CID'];

            // Redirect to profile.php (user profile page)
            header("Location: profile.php");
            exit();
        } else {
            echo "Wrong username/email or password";
        }
    } else {
        echo "Wrong username/email or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            background-color: #333;
            color: #fff;
            padding: 15px;
            margin: 0;
            text-align: center;
        }

        form {
            text-align: center;
            margin: 15px 0;
        }

        button {
            background-color: #333;
            color: #fff;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #555;
        }

        p {
            text-align: center;
            margin: 15px 0;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .section {
            margin-top: 20px;
            text-align: center;
        }

        .section-title {
            font-size: 18px;
            margin-bottom: 10px;
        }

        li {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin: 10px auto;
            max-width: 300px;
        }

        li:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="login.php">
        <!-- Login form fields -->
        <input type="text" name="username_or_email" placeholder="Username or Email"><br><br>
        <input type="password" name="password" placeholder="Password"><br><br>
        
        <input type="submit" name="login" value="Login">
    </form>
    <form method="get" action="Register.php">
    <input type="submit" value="Register Here">
</form>

</body>
</html>
