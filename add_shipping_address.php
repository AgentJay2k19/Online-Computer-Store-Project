<?php
session_start();
include('includes/db.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        // Retrieve posted data from the form
        $saname = $_POST['saname'];
        $recipientname = $_POST['recipientname'];
        $street = $_POST['street'];
        $snumber = $_POST['snumber'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $state = $_POST['state'];
        $country = $_POST['country'];

        // Insert query to add a new shipping address
        $sql = "INSERT INTO SHIPPING_ADDRESS (CID, SANAME, RECEPIENTNAME, STREET, SNUMBER, CITY, ZIP, STATE, COUNTRY) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissss", $user_id, $saname, $recipientname, $street, $snumber, $city, $zip, $state, $country);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Shipping address added successfully.";
        } else {
            echo "Failed to add shipping address.";
        }
        $stmt->close();
    } else {
        echo "User not logged in.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Shipping Address</title>
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
    <h2>Add Shipping Address</h2>
    <form method="post" action="">
        <label for="saname">Shipping Address Name:</label>
        <input type="text" id="saname" name="saname" required><br><br>

        <label for="recipientname">Recipient Name:</label>
        <input type="text" id="recipientname" name="recipientname"><br><br>

        <label for="street">Street:</label>
        <input type="text" id="street" name="street"><br><br>

        <label for="snumber">Street Number:</label>
        <input type="number" id="snumber" name="snumber"><br><br>

        <label for="city">City:</label>
        <input type="text" id="city" name="city"><br><br>

        <label for="zip">ZIP Code:</label>
        <input type="number" id="zip" name="zip"><br><br>

        <label for="state">State:</label>
        <input type="text" id="state" name="state"><br><br>

        <label for="country">Country:</label>
        <input type="text" id="country" name="country"><br><br>

        <input type="submit" value="Add Shipping Address">
    </form>
</body>
</html>
