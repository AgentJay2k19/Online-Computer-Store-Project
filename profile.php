<?php
session_start();
include('includes/db.php'); // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch and display user data
$user_id = $_SESSION['user_id'];

// Fetch all user data from the CUSTOMER table
$sql = "SELECT * FROM CUSTOMER WHERE CID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Store user details
    $cid = $row['CID'];
    $first_name = $row['FNAME'];
    $last_name = $row['LNAME'];
    $email = $row['EMAIL'];
    $address = $row['ADDRESS'];
    $phone = $row['PHONE'];
    $status = $row['STATUS'];
    // ... other user details
} else {
    echo "User data not found.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <style>
        <style>
        table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    margin-bottom: 30px;
    background-color: rgba(255, 235, 205, 0.5); /* Light transparent orange */
}

th {
    background-color: #6C7A89; /* Blueish-gray */
    color: white;
}

tr:nth-child(even) {
    background-color: #EAEDED; /* Light gray for even rows */
}

tr:nth-child(odd) {
    background-color: #FFFFFF; /* White for odd rows */
}

tr:hover {
    background-color: #D5DBDB; /* Light gray on hover */
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

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
    </style>
</head>
<body>
    <h2>User Profile</h2>
    <!-- Display all user details here -->
    <p>CID: <?php echo $cid; ?></p>
    <p>Name: <?php echo $first_name . " " . $last_name; ?></p>
    <p>Email: <?php echo $email; ?></p>
    <p>Address: <?php echo $address; ?></p>
    <p>Phone: <?php echo $phone; ?></p>
    <p>Status: <?php echo $status; ?></p>

        <!-- Buttons for adding and viewing credit card details -->
        <form method="post" action="add_credit_card.php">
        <input type="hidden" name="stored_card_id" value="<?php echo $user_id; ?>">
        <input type="submit" value="Add Credit Card">
    </form>
    <form method="post" action="view_credit_card.php">
        <input type="hidden" name="stored_card_id" value="<?php echo $user_id; ?>">
        <input type="submit" value="View Credit Card Details">
    </form>
    <!-- Add Shipping Address button -->
<form method="get" action="add_shipping_address.php">
    <input type="submit" value="Add Shipping Address">
</form>

<!-- View Shipping Address Details button -->
<form method="get" action="view_shipping_address.php">
    <input type="submit" value="View Shipping Address Details">
</form>

<form method="get" action="shopping_basket_management.php">
    <input type="submit" value="Add Products to basket">
</form>

<form method="get" action="view_basket.php">
    <input type="submit" value="View Shopping Basket">
</form>
    <form method="post" action="logout.php">
        <input type="submit" value="Logout">
    </form>
</body>
</html>
