<?php
session_start();
include('includes/db.php'); // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve credit card details from the form
    $cc_number = $_POST['cc_number'] ?? '';
    $sec_number = $_POST['sec_number'] ?? '';
    $owner_name = $_POST['owner_name'] ?? '';
    $cc_type = $_POST['cc_type'] ?? '';
    $billing_address = $_POST['billing_address'] ?? '';
    $exp_date = $_POST['exp_date'] ?? '';

    // Basic form validation (perform more comprehensive validation as needed)
    if (empty($cc_number) || empty($sec_number) || empty($owner_name) || empty($cc_type) || empty($billing_address) || empty($exp_date)) {
        echo "Please fill in all fields.";
    } elseif (!preg_match('/^\d{3}$/', $sec_number)) {
        echo "Security number should be a 3-digit number.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $exp_date)) {
        echo "Expiry date should be in YYYY-MM-DD format.";
    } else {
        // Get storedcardid (CID) from the session (assuming storedcardid is CID)
        $stored_card_id = $_SESSION['user_id'];

        // Insert credit card details into the table using storedcardid (CID)
        $sql = "INSERT INTO CREDIT_CARD (CCNUMBER, SECNUMBER, OWNERNAME, CCTYPE, BILADDRESS, EXPDATE, STOREDCARDID) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssss", $cc_number, $sec_number, $owner_name, $cc_type, $billing_address, $exp_date, $stored_card_id);
        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->affected_rows > 0) {
            echo "Credit card details added successfully.";
        } else {
            echo "Failed to add credit card details.";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
        exit(); // Stop further execution after processing the form
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Credit Card</title>
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
    <h2>Add Credit Card</h2>
    <!-- Credit card form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="cc_number">Credit Card Number:</label>
        <input type="text" id="cc_number" name="cc_number"><br><br>

        <label for="sec_number">Security Number:</label>
        <input type="text" id="sec_number" name="sec_number"><br><br>

        <label for="owner_name">Owner Name:</label>
        <input type="text" id="owner_name" name="owner_name"><br><br>

        <label for="cc_type">Card Type:</label>
        <input type="text" id="cc_type" name="cc_type"><br><br>

        <label for="billing_address">Billing Address:</label>
        <input type="text" id="billing_address" name="billing_address"><br><br>

        <label for="exp_date">Expiration Date (YYYY-MM-DD):</label>
        <input type="text" id="exp_date" name="exp_date"><br><br>

        <input type="submit" value="Add Credit Card">
    </form>
</body>
</html>
