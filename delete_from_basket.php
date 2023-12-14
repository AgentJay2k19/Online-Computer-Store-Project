<?php
session_start();
include('includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Delete the product from the basket
    $delete_query = "DELETE FROM basket WHERE CID = ? AND BID = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("ss", $_SESSION['user_id'], $product_id);
    $delete_stmt->execute();

    if ($delete_stmt->affected_rows > 0) {
        echo "Product deleted from the basket successfully.";
    } else {
        echo "Failed to delete product from the basket.";
    }
    $delete_stmt->close();
} else {
    echo "Invalid request method or missing product ID.";
}
?>
