<?php
session_start();
include('includes/db.php'); // Your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['shipping_address_id'])) {
    $shipping_address_id = $_POST['shipping_address_id'];

    // Check if the shipping address exists before attempting deletion
    $check_query = "SELECT * FROM SHIPPING_ADDRESS WHERE CID = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $shipping_address_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Prepare and execute the delete query
        $delete_query = "DELETE FROM SHIPPING_ADDRESS WHERE CID = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $shipping_address_id);
        $delete_stmt->execute();

        if ($delete_stmt->affected_rows > 0) {
            echo "Shipping address deleted successfully.";
        } else {
            echo "Failed to delete shipping address.";
        }
        $delete_stmt->close();
    } else {
        echo "Shipping address does not exist.";
    }
    $check_stmt->close();
} else {
    echo "Invalid request method or no shipping address ID received.";
}
?>