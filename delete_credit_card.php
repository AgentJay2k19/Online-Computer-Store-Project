<?php
session_start();
include('includes/db.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form was submitted with data
    if (isset($_POST['cc_number'])) {
        // Retrieve credit card number from the form
        $ccNumber = $_POST['cc_number'];

        // Delete query to remove the credit card entry
        $sql = "DELETE FROM CREDIT_CARD WHERE CCNUMBER = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ccNumber);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Credit card deleted successfully.";
        } else {
            echo "Failed to delete credit card.";
        }
        $stmt->close();
    } else {
        echo "No credit card number received for deletion.";
    }
} else {
    echo "Invalid request method.";
}
?>
