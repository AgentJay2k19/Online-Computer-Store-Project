<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    // Retrieve input dates
    // $start_date = $_POST['start_date'];
    // $end_date = $_POST['end_date'];

    $begin_date = '2023-01-01';
    $end_date = '2023-12-31';

    // Connect to your database (replace with your database credentials)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "company";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query to get the most frequently sold products within the date range
    $sql = "
    SELECT ai.PID AS ProductID, p.PName AS ProductName, COUNT(*) AS TotalSales
    FROM `transaction` tr
    JOIN appears_in ai ON tr.BID = ai.BID
    JOIN product p ON ai.PID = p.PID
    WHERE tr.TDate BETWEEN ? AND ?
    GROUP BY ai.PID, p.PName
    ORDER BY TotalSales DESC;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $begin_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display the report
    echo "<h2>Product Sales Report</h2>";
    echo "<p>Report for the period: $begin_date to $end_date</p>";
    echo "<table border='1'>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Total Sales</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['ProductID']}</td>
                <td>{$row['ProductName']}</td>
                <td>{$row['TotalSales']}</td>
              </tr>";
    }
   
    echo "</table>";
  
    // Close connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect back to the form if there's no valid input
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title></title><style>
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
</head>
<body>
    <h2> Sales Statistics</h2>

    <!-- Form for date range selection and displaying most frequently sold products -->
    <form method="post" action="total_amount_charged_percreditcard.php">
        <input type="submit" value="Amount Charged Per Credit Card">
    </form>
    <form method="post" action="topten_best_customer.php">
        <input type="submit" value="Top Ten Best Customers">
    </form>
    <form method="post" action="most_frequently_sold.php">
        <input type="submit" value="Get Most Frequently Sold Products">
    </form>
    <form method="post" action="highest_distinct_customer.php">
        <input type="submit" value="Highest Distinct Customer">
    </form>
    <form method="post" action="averageReport.php">
        <input type="submit" value="Average Report">
    </form>
    <form method="post" action="maxBasket.php">
        <input type="submit" value="Max Basket">
    </form>
    </body>
</html>
