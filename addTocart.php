<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to add items to your cart.";
    exit();
}

if (isset($_GET['pid']) && isset($_GET['size']) && !empty($_GET['size'])) {
    $pid = $_GET['pid'];
    $size = $_GET['size'];
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM cart WHERE user_id = ? AND pid = ? AND size = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $user_id, $pid, $size);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Product with selected size is already in the cart.";
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

