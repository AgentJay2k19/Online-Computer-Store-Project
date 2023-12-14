<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Update appears_in table for the given user and product
    $update_query = "UPDATE appears_in SET QUANTITY = ? WHERE BID = (SELECT BID FROM basket WHERE CID = ?) AND PID = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("iss", $quantity, $user_id, $product_id);
    $stmt->execute();
    $stmt->close();

    header("Location: view_basket.php");
    exit();
} else {
    // Redirect to the view_shopping_basket.php page if no POST data received
    header("Location: view_basket.php");
    exit();
}
?>
