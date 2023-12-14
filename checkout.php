<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    $user_id = $_SESSION['user_id'];

    // Assuming the transaction table columns match the values being used
    $insert_transaction = "INSERT INTO transaction (BID, CCNUMBER, CID, SAName, TDate, TTag)
                           SELECT basket.BID, credit_card.CCNUMBER, basket.CID, shipping_address.SAName, NOW(), 'Not Delivered'
                           FROM basket 
                           JOIN shipping_address ON basket.CID = shipping_address.CID 
                           JOIN credit_card ON /* Add the condition to join credit_card table here based on your actual structure */
                           WHERE basket.CID = ?";

    $stmt = $conn->prepare($insert_transaction);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->close();

    // Now, clear the user's shopping basket after the checkout
    $clear_basket = "DELETE FROM appears_in WHERE BID IN (SELECT BID FROM basket WHERE CID = ?)";
    $stmt_clear = $conn->prepare($clear_basket);
    $stmt_clear->bind_param("s", $user_id);
    $stmt_clear->execute();
    $stmt_clear->close();

    // Redirect to a confirmation page or any other desired page after checkout
    header("Location: confirmation.php");
    exit();
} else {
    // Redirect if the checkout button was not clicked
    header("Location: view_shopping_basket.php");
    exit();
}
?>
