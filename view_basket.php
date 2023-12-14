<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT p.*, ai.QUANTITY AS BasketQuantity, op.OFFERPRICE
        FROM appears_in ai 
        INNER JOIN product p ON ai.PID = p.PID 
        LEFT JOIN offer_product op ON p.PID = op.PID
        WHERE ai.BID = (
            SELECT BID FROM basket WHERE CID = ?
        )";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Shopping Basket</title>
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

    <!-- Add additional styles if needed -->
</head>
<body>
    <h2>Shopping Basket</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Offer Price</th>
                <th>Quantity in Basket</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $row["PID"]; ?></td>
                        <td><?php echo $row["PName"]; ?></td>
                        <td>
                            <?php
                            // Calculate the displayed price considering the offer price
                            if (isset($row["OFFERPRICE"])) {
                                $discounted_price = $row["PPrice"] - $row["OFFERPRICE"];
                                echo $discounted_price;
                            } else {
                                echo $row["PPrice"];
                            }
                            ?>
                        </td>
                        <td><?php echo $row["Description"]; ?></td>
                        <td><?php echo isset($row["OFFERPRICE"]) ? $row["OFFERPRICE"] : ''; ?></td>
                        <td><?php echo $row["BasketQuantity"]; ?></td>
                        <td>
                            <form method="post" action="update_basket.php">
                                <input type="hidden" name="product_id" value="<?php echo $row["PID"]; ?>">
                                <input type="number" name="quantity" value="<?php echo $row["BasketQuantity"]; ?>">
                                <input type="submit" value="Update">
                            </form>
                          
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='7'>No items found in the shopping basket.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <form method="post" action="transaction_details.php">
        <input type="submit" name="checkout" value="Checkout">
    </form>
</body>
</html>
