<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve necessary data to populate the form fields
$select_query = "SELECT CCNUMBER, SECNUMBER, OWNERNAME, CCTYPE, BILADDRESS, EXPDATE 
                 FROM credit_card 
                 WHERE STOREDCARDID = ?";
$stmt_select = $conn->prepare($select_query);
$stmt_select->bind_param("s", $user_id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$row = $result->fetch_assoc();

// Retrieve total price from appears_in table without considering the offer price
$total_price_query = "SELECT SUM(PRICESOLD) AS TotalPrice
                      FROM appears_in ai
                      JOIN basket b ON ai.BID = b.BID
                      JOIN product p ON ai.PID = p.PID
                      LEFT JOIN offer_product op ON p.PID = op.PID
                      WHERE b.CID = ?
                      GROUP BY b.BID";
$stmt_total_price = $conn->prepare($total_price_query);
$stmt_total_price->bind_param("s", $user_id);
$stmt_total_price->execute();
$result_total_price = $stmt_total_price->get_result();
$row_total_price = $result_total_price->fetch_assoc();

$total_price = $row_total_price['TotalPrice'];

// HTML form to enter transaction details
?>


<!DOCTYPE html>
<html>
<head>
    <title>Enter Transaction Details</title>
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

    </style>
</head>
<body>
    <h2>Enter Transaction Details</h2>
    <form action="process_transaction.php" method="post">
        <label for="cc_number">Credit Card Number:</label>
        <input type="text" id="cc_number" name="cc_number" value="<?php echo $row['CCNUMBER']; ?>" required><br><br>

        <label for="sec_number">Security Number:</label>
        <input type="text" id="sec_number" name="sec_number" value="<?php echo $row['SECNUMBER']; ?>" required><br><br>

        <label for="owner_name">Owner's Name:</label>
        <input type="text" id="owner_name" name="owner_name" value="<?php echo $row['OWNERNAME']; ?>" required><br><br>

        <label for="cc_type">Credit Card Type:</label>
        <input type="text" id="cc_type" name="cc_type" value="<?php echo $row['CCTYPE']; ?>" required><br><br>

        <label for="billing_address">Billing Address:</label>
        <input type="text" id="billing_address" name="billing_address" value="<?php echo $row['BILADDRESS']; ?>" required><br><br>

        <label for="exp_date">Expiration Date:</label>
        <input type="text" id="exp_date" name="exp_date" value="<?php echo $row['EXPDATE']; ?>" required><br><br>

        <label for="total_price">Total Price:</label>
        <input type="text" id="total_price" name="total_price" value="<?php echo $total_price; ?>" required readonly><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
    

<!-- <?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

