<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$begin_date = '2023-12-01';
$end_date = '2023-12-15';

$sql = "SELECT subcustomer.PID, subcustomer.cnt
        FROM (
            SELECT PID, COUNT(CID) AS cnt
            FROM TRANSACTION NATURAL JOIN APPEARS_IN
            WHERE TDATE BETWEEN ? AND ?
            GROUP BY PID
        ) AS subcustomer
        WHERE subcustomer.cnt = (
            SELECT MAX(subcustomer2.cnt)
            FROM (
                SELECT PID, COUNT(CID) AS cnt
                FROM TRANSACTION NATURAL JOIN APPEARS_IN
                WHERE TDATE BETWEEN ? AND ?
                GROUP BY PID
            ) AS subcustomer2
        )
        GROUP BY subcustomer.PID";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $begin_date, $end_date, $begin_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Product ID: " . $row['PID'] . " | Customers: " . $row['cnt'] . "<br>";
    }
} else {
    echo "No results found for the given time period.";
}
?>
