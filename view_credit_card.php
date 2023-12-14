<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM CREDIT_CARD WHERE STOREDCARDID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Credit Card Details</title>
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
    <h2>Credit Card Details</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Card Number</th>
                <th>Security Number</th>
                <th>Owner Name</th>
                <th>Card Type</th>
                <th>Billing Address</th>
                <th>Expiry Date</th>
                <th>Stored Card ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row["CCNUMBER"]; ?></td>
                    <td><?php echo $row["SECNUMBER"]; ?></td>
                    <td><?php echo $row["OWNERNAME"]; ?></td>
                    <td><?php echo $row["CCTYPE"]; ?></td>
                    <td><?php echo $row["BILADDRESS"]; ?></td>
                    <td><?php echo $row["EXPDATE"]; ?></td>
                    <td><?php echo $row["STOREDCARDID"]; ?></td>
                    <td>
                        <!-- Update form -->
                        <form method="post" action="update_credit_card.php">
                            <?php foreach ($row as $key => $value) { ?>
                                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
                            <?php } ?>
                            <input type="submit" value="Update">
                        </form>
                        <!-- Delete form -->
                        <form method="post" action="delete_credit_card.php">
                            <input type="hidden" name="cc_number" value="<?php echo $row["CCNUMBER"]; ?>">
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</body>
</html>
