<?php
session_start();
include('includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cc_number'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    $user_id = $_SESSION['user_id'];

    // Retrieve form data
    $cc_number = $_POST['cc_number'];
    // Other form fields...

    // Prepare and execute the query to fetch necessary data
    $select_query = "SELECT basket.BID, credit_card.CCNUMBER, basket.CID, shipping_address.SAName, SUM(appears_in.PRICESOLD) AS TotalPrice
                     FROM basket 
                     JOIN shipping_address ON basket.CID = shipping_address.CID 
                     JOIN credit_card ON credit_card.STOREDCARDID = basket.CID
                     JOIN appears_in ON basket.BID = appears_in.BID
                     WHERE basket.CID = ?
                     GROUP BY basket.BID, credit_card.CCNUMBER, basket.CID, shipping_address.SAName";
    $stmt_select = $conn->prepare($select_query);
    $stmt_select->bind_param("s", $user_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $row = $result->fetch_assoc();

    // Insert the retrieved data into the transaction table
    $insert_transaction = "INSERT INTO `transaction` (BID, CCNUMBER, CID, SAName, TDate, TTag, TotalPrice) 
                           VALUES (?, ?, ?, ?, NOW(), 'Not Delivered', ?)";
    $stmt_insert = $conn->prepare($insert_transaction);
    $stmt_insert->bind_param("sssss", $row['BID'], $cc_number, $row['CID'], $row['SAName'], $row['TotalPrice']);
    $stmt_insert->execute();
    $stmt_insert->close();

    // Redirect after successful transaction
    header("Location: Profile.php");
    exit();
} else {
    header("Location: transaction_details.php");
    exit();
}
?>
