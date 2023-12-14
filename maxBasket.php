<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the begin and end dates from your form or any other input method
// $begin_date = $_POST['begin_date']; // Replace this with the actual method of getting begin date
// $end_date = $_POST['end_date']; // Replace this with the actual method of getting end date

// Define your date range
$begin_date = '2023-01-01';
$end_date = '2023-12-31';

// Prepare the SQL query to compute the maximum basket total amount per credit card
$sql = "SELECT tr.CCNUMBER, MAX(tr.TotalPrice) AS MaxBasketTotal
        FROM `transaction` tr
        JOIN basket b ON tr.BID = b.BID
        WHERE tr.CID = ? AND tr.TDate BETWEEN ? AND ?
        GROUP BY tr.CCNUMBER";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $user_id, $begin_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

// Display the results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Credit Card Number: " . $row['CCNUMBER'] . " | Max Basket Total: " . $row['MaxBasketTotal'] . "<br>";
    }
} else {
    echo "No results found for the given time period.";
}
?>
