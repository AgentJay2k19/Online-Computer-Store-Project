<?php
include('includes/db.php');

// Define your date range
$begin_date = '2023-01-01';
$end_date = '2023-12-31';

// Calculate average selling price per product type within the given date range
$query = "SELECT p.PType, AVG(ai.PRICESOLD) AS AvgPrice
          FROM appears_in ai
          JOIN product p ON ai.PID = p.PID
          JOIN transaction t ON ai.BID = t.BID
          WHERE t.TDate BETWEEN ? AND ?
          GROUP BY p.PType";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $begin_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report: Average Selling Price per Product Type</title>
    <style>
        /* Add your CSS styling here */
    </style>
</head>
<body>
    <h2>Report: Average Selling Price per Product Type</h2>
    <table>
        <thead>
            <tr>
                <th>Product Type</th>
                <th>Average Selling Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['PType'] . "</td>";
                echo "<td>" . $row['AvgPrice'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
