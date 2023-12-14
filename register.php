<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
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
    <h2>Registration</h2>
    <?php
    // Include the database connection file here
    include('includes/db.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $fname = htmlspecialchars($_POST['fname']);
        $lname = htmlspecialchars($_POST['lname']);
        $address = htmlspecialchars($_POST['address']);
        $phone = htmlspecialchars($_POST['phone']);
        $status = htmlspecialchars($_POST['status']); // Status from the form
        
        // Insert data into the CUSTOMER table
        $sql = "INSERT INTO CUSTOMER (CID, FNAME, LNAME, EMAIL, ADDRESS, PHONE, STATUS, PASSWORD) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $username, $fname, $lname, $email, $address, $phone, $status, $password);
        $stmt->execute();
        
        echo "Registration successful!";
    }
    ?>
    
    <form method="post" action="register.php">
        <!-- Registration form fields -->
        <input type="text" name="username" placeholder="Username"><br><br>
        <input type="email" name="email" placeholder="Email"><br><br>
        <input type="password" name="password" placeholder="Password"><br><br>
        <input type="text" name="fname" placeholder="First Name"><br><br>
        <input type="text" name="lname" placeholder="Last Name"><br><br>
        <input type="text" name="address" placeholder="Address"><br><br>
        <input type="text" name="phone" placeholder="Phone"><br><br>
        <input type="text" name="status" placeholder="Status"><br><br> <!-- Status field -->
        
        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>
