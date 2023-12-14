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

$sql = "SELECT c.STOREDCARDID, SUM(subtotal.TOTAL_AMOUNT) AS TOTAL_CHARGE
        FROM CREDIT_CARD c 
        NATURAL JOIN (
            SELECT CCNUMBER, SUM(a.PRICESOLD) AS TOTAL_AMOUNT
            FROM TRANSACTION t 
            NATURAL JOIN APPEARS_IN a
            GROUP BY t.CCNUMBER
        ) as subtotal
        GROUP BY c.STOREDCARDID
        ORDER BY TOTAL_CHARGE DESC
        LIMIT 10;";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "STOREDCARDID: " . $row['STOREDCARDID'] . " | Total Charge: " . $row['TOTAL_CHARGE'] . "<br>";
    }
} else {
    echo "No results found for the given time period.";
}
?>
