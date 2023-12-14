<?php
session_start();
include('includes/db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the user has a basket ID, if not, generate a new one
$sql = "SELECT BID FROM basket WHERE CID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Generate a new basket ID for the user and insert it into the basket table
    $basket_id = generateBasketID(); // Implement a function to generate a unique basket ID
    $insert_basket = "INSERT INTO basket (BID, CID) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($insert_basket);
    $stmt_insert->bind_param("ss", $basket_id, $user_id);
    $stmt_insert->execute();
    $stmt_insert->close();
} else {
    // Fetch the existing basket ID for the user
    $row = $result->fetch_assoc();
    $basket_id = $row['BID'];
}



// Now, insert or update the product into appears_in table
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price_sold = getPriceForProduct($conn, $product_id, $quantity); // Retrieve the price for the product

    // Check if the product already exists in the appears_in table for this user
    $check_query = "SELECT * FROM appears_in WHERE BID = ? AND PID = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ss", $basket_id, $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Product already exists, update the quantity
        $update_query = "UPDATE appears_in SET QUANTITY = QUANTITY + ? WHERE BID = ? AND PID = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("iss", $quantity, $basket_id, $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Product doesn't exist, insert into appears_in table
        $insert_query = "INSERT INTO appears_in (BID, PID, QUANTITY, PRICESOLD) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("ssdi", $basket_id, $product_id, $quantity, $price_sold);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    // Display a success message
    echo "Product successfully added to the basket!";
}


// Functions to generate Basket ID and retrieve product price can be implemented separately

// Function to generate a unique basket ID
function generateBasketID($length = 3) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $basket_id = '';
    $max = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $basket_id .= $characters[rand(0, $max)];
    }

    return $basket_id;
}

// Function to get the price for a product
function getPriceForProduct($conn, $product_id, $quantity) {
    $query = "SELECT PPrice FROM product WHERE PID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $regular_price = $row['PPrice'];
        $special_price = getSpecialPriceForProduct($conn, $product_id);

        if ($special_price !== null && $special_price < $regular_price) {
            return $special_price * $quantity;
        } else {
            return $regular_price * $quantity;
        }
    }

    return null; // Handle if product not found
}

// Function to get special price for a product from offer_product table
function getSpecialPriceForProduct($conn, $product_id) {
    $query = "SELECT OFFERPRICE FROM offer_product WHERE PID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['OFFERPRICE'];
    }

    return null; // No special price found
}

?>
