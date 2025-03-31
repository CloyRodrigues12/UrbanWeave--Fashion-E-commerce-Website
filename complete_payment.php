<?php
session_start();
include('db.php'); // Database connection
$expected_delivery = date('Y-m-d', strtotime('+6 days'));
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture POST data
    $user_id = $_SESSION['user_id'] ?? null;
    $order_status = 'Pending'; // or any other initial status
    

    if (!$user_id) {
        echo "Please log in to proceed.";
        exit();
    }   

    // Insert order items (from cart) into order_items table
    $query_cart_items = "SELECT * FROM cart WHERE user_id = ?";
    $stmt_cart = $conn->prepare($query_cart_items);
    $stmt_cart->bind_param("i", $user_id);
    $stmt_cart->execute();
    $cart_items = $stmt_cart->get_result();

    while ($item = $cart_items->fetch_assoc()) {
        $product_id = $item['pid'];
        $quantity = $item['quantity'];
        $size = $item['size'];
    
        // Fetch the price from the products table using the product ID
        $query_product_price = "SELECT price FROM products WHERE pid = ?";
        $stmt_product_price = $conn->prepare($query_product_price);
        $stmt_product_price->bind_param("i", $product_id);
        $stmt_product_price->execute();
        $result_price = $stmt_product_price->get_result();
        $product = $result_price->fetch_assoc();
        $price = $product['price'];
        
        $total = $price * $quantity;
    
        $total_amount = 0;

        $total_amount += $price * $quantity;
        
    // Insert into orders table
    $query = "INSERT INTO orders (user_id, total_amount, order_status,expected_delivery) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("idss", $user_id, $total_amount, $order_status,$expected_delivery);
    $stmt->execute();
    $order_id = $stmt->insert_id; // Get the order ID of the inserted order
        // Insert into order_items table
        $query_order_items = "INSERT INTO order_items (order_id, product_id, quantity, size, price, total)
        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_order_items = $conn->prepare($query_order_items);
        $stmt_order_items->bind_param("iiisdd", $order_id, $product_id, $quantity, $size, $price, $total);
        $stmt_order_items->execute();
    }



// Insert into payments table after order is created

$payment_method = $_POST['payment_method'] ?? 'Unknown'; // e.g., 'Credit Card' or 'UPI'
$upi_method = $_POST['upi_method'] ?? null; // e.g., 'GPay', 'Amazon Pay', etc. (only for UPI)
$phone = $_POST['upi_phone'] ?? null; // Phone number associated with the payment
$payment_status = 'Completed';
$payment_date = date('Y-m-d H:i:s'); // Current timestamp

$query_payment = "INSERT INTO payments (order_id, payment_method, upi_method, phone, payment_status, payment_date) VALUES (?, ?, ?, ?, ?, ?)";
$stmt_payment = $conn->prepare($query_payment);
$stmt_payment->bind_param("ississ", $order_id, $payment_method, $upi_method, $phone, $payment_status, $payment_date);

// Execute the query and check for success
if ($stmt_payment->execute()) {
    echo "Payment information saved successfully.";
} else {
    echo "Error saving payment information: " . $stmt_payment->error;
}


    // Assuming you have already processed the order and saved it in the database
    
    // Clear the cart after placing the order
    $query = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "<p>Your order has been placed successfully. The items have been removed from your cart.</p>";
    } else {
        echo "<p>Order placed, but there was an issue clearing your cart. Please try again later.</p>";
    }

    

    // Redirect to the payment confirmation page
    header("Location: payment_confirmation.php?order_id=$order_id");
    exit();
}
?>
