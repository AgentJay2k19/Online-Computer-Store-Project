<?php
session_start();
include('includes/db.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['shipping_address_id'])) {
        $shippingAddressId = $_POST['shipping_address_id'];

        // Fetch the existing shipping address details from the database
        $sql = "SELECT * FROM SHIPPING_ADDRESS WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $shippingAddressId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the existing details
            $row = $result->fetch_assoc();
            // Populate the form fields with the fetched data
            // Example:
            $saname = $row['SANAME'];
            $recipientName = $row['RECEPIENTNAME'];
            $street = $row['STREET'];
            $snumber = $row['SNUMBER'];
            $city = $row['CITY'];
            $zip = $row['ZIP'];
            $state = $row['STATE'];
            $country = $row['COUNTRY'];
        } else {
            echo "Shipping address not found.";
        }
        $stmt->close();
    } else {
        echo "No shipping address ID received.";
    }
} else {
    echo "Invalid request method.";
}
?>

<!-- HTML form for updating shipping address details -->
<form method="post" action="update_shipping_address_process.php">
    <!-- Hidden field to pass the shipping address ID -->
    <input type="hidden" name="shipping_address_id" value="<?php echo $shippingAddressId; ?>">

    <!-- Form fields to update shipping address -->
    <label for="saname">SA Name:</label>
    <input type="text" id="saname" name="saname" value="<?php echo $saname; ?>"><br><br>

    <label for="recipientName">Recipient Name:</label>
    <input type="text" id="recipientName" name="recipientName" value="<?php echo $recipientName; ?>"><br><br>

    <!-- Include other fields for street, number, city, zip, state, country -->
    <label for="street">Street:</label>
    <input type="text" id="street" name="street" value="<?php echo $street; ?>"><br><br>

    <label for="snumber">Number:</label>
    <input type="text" id="snumber" name="snumber" value="<?php echo $snumber; ?>"><br><br>

    <label for="city">City:</label>
    <input type="text" id="city" name="city" value="<?php echo $city; ?>"><br><br>

    <label for="zip">Zip:</label>
    <input type="text" id="zip" name="zip" value="<?php echo $zip; ?>"><br><br>

    <label for="state">State:</label>
    <input type="text" id="state" name="state" value="<?php echo $state; ?>"><br><br>

    <label for="country">Country:</label>
    <input type="text" id="country" name="country" value="<?php echo $country; ?>"><br><br>

    <input type="submit" value="Update Shipping Address">
</form>
