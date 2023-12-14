<?php
session_start();
include('includes/db.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['CCNUMBER'])) {
        // Retrieve updated credit card details from the form
        $ccNumber = $_POST['CCNUMBER'];
        $secNumber = $_POST['SECNUMBER'];
        $ownerName = $_POST['OWNERNAME'];
        $ccType = $_POST['CCTYPE'];
        $bilAddress = $_POST['BILADDRESS'];
        $expDate = $_POST['EXPDATE'];

        // Update query to modify credit card details
        $sql = "UPDATE CREDIT_CARD SET SECNUMBER = ?, OWNERNAME = ?, CCTYPE = ?, BILADDRESS = ?, EXPDATE = ? WHERE CCNUMBER = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssi", $secNumber, $ownerName, $ccType, $bilAddress, $expDate, $ccNumber);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Credit card details updated successfully.";
        } else {
            echo "Failed to update credit card details.";
        }
        $stmt->close();
    } else {
        echo "No credit card number received.";
    }
} else {
    echo "Invalid request method.";
}
?>

<!-- Display the update form -->
<!-- Use the retrieved data to pre-fill the form fields -->
<form method="post" action="update_credit_card.php">
    <input type="hidden" name="CCNUMBER" value="<?php echo $ccNumber; ?>">
    <input type="text" name="SECNUMBER" value="<?php echo $secNumber; ?>">
    <input type="text" name="OWNERNAME" value="<?php echo $ownerName; ?>">
    <input type="text" name="CCTYPE" value="<?php echo $ccType; ?>">
    <input type="text" name="BILADDRESS" value="<?php echo $bilAddress; ?>">
    <input type="text" name="EXPDATE" value="<?php echo $expDate; ?>">
    <input type="submit" value="Update Credit Card">
</form>
