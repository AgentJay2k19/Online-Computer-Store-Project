<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$begin_date = '2023-01-01';
$end_date = '2023-12-31';

$sql = "SELECT CCNUMBER, SUM(PRICESOLD) AS TOTAL_AMOUNT
FROM TRANSACTION NATURAL JOIN APPEARS_IN
GROUP BY CCNUMBER;";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Credit Card Number: " . $row['CCNUMBER'] . " | Total Amount Spend: " . $row['TOTAL_AMOUNT'] . "<br>";
    }
} else {
    echo "No results found for the given time period.";
}
?>
