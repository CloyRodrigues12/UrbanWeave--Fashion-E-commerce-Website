<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to add items to your cart.";
    exit();
}

if (isset($_POST['pid'], $_POST['size'])) {
    $pid = $_POST['pid'];
    $size = $_POST['size'];
    $user_id = $_SESSION['user_id'];

    // Check if the product with the selected size already exists in the cart
    $query = "SELECT * FROM cart WHERE user_id = ? AND pid = ? AND size = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $user_id, $pid, $size);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "This product with the selected size is already in your cart.";
    } else {
        $insertQuery = "INSERT INTO cart (user_id, pid, size, quantity) VALUES (?, ?, ?, 1)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iis", $user_id, $pid, $size);

        if ($stmt->execute()) {
            echo "Product added to cart successfully!";
        } else {
            echo "Error adding product to cart.";
        }
    }
} else {
    echo "No product or size selected.";
}
?>
