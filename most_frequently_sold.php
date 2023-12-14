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

$sql = "SELECT ai.PID, p.PName, COUNT(*) AS Frequency
        FROM appears_in ai
        JOIN `transaction` tr ON ai.BID = tr.BID
        JOIN product p ON ai.PID = p.PID
        WHERE tr.TDate BETWEEN ? AND ?
        GROUP BY ai.PID, p.PName
        ORDER BY Frequency DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $begin_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Product ID: " . $row['PID'] . " | Product Name: " . $row['PName'] . " | Frequency Sold: " . $row['Frequency'] . "<br>";
    }
} else {
    echo "No results found for the given time period.";
}
?>
