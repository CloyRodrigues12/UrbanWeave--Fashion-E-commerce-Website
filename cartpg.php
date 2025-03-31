<?php
session_start();
include('db.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "Please log in to view your cart.";
    exit();
}

$query = "SELECT c.*, p.pname, p.pcategory, p.pimg1, p.price, c.size 
        FROM cart AS c 
        JOIN products AS p ON c.pid = p.pid 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<title>Your Cart</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f4f8;
    height: 100vh;
    margin: 0;
    padding:5px;
}
h2 {
    text-align: center;
    margin-bottom: 20px;
    }
        table {
            width: 100%;border-collapse: collapse;margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);background-color: #fff;
        }
        th, td {
            padding: 15px;text-align: center;border: 1px solid #ddd;}
        th {
            background-color: #4CAF50;color: white;}
        img {
            width: 80px;height: auto;}
        .quantity {width: 50px;}
        
        .update-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .update-btn:hover {
            background-color: #45a049;
        }
        .remove-btn { background-color: #f44336;color: white;border: none;
            padding: 5px 10px;cursor: pointer;transition: background-color 0.3s;
        }
        .remove-btn:hover {
            background-color: #e53935;
        }

.cart {
    display: inline-block; /* Allow padding and margins to be applied */
    background-color: #4CAF50; /* Green background */
    color: white; /* White text */
    padding: 10px 20px; /* Vertical and horizontal padding */
    border-radius: 5px; /* Rounded corners */
    text-decoration: none; /* Remove underline */
    font-weight: bold; /* Bold text */
    font-size: 16px; /* Font size */
    transition: background-color 0.3s ease, transform 0.2s;
    margin-top: 15px;
}

.cart:hover {
    background-color:orange; 
    transform: translateY(-2px);
}
.cart:active {
    transform: translateY(1px);
}
</style>
</head>
<body>

<?php include 'navbar.php'; ?> <br>

<h2>Your Cart</h2>
<?php
if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Size</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="uploads/<?php echo htmlspecialchars($row['pimg1']); ?>" alt=""></td>
                <td><?php echo htmlspecialchars($row['pname']); ?></td>
                <td><?php echo htmlspecialchars($row['pcategory']); ?></td>
                <td>Rs <?php echo htmlspecialchars($row['price']); ?></td>
                
                <!-- Quantity Input -->
                <td>
                    <input type="number" id="quantity-<?php echo $row['pid']; ?>" value="<?php echo htmlspecialchars($row['quantity']); ?>" 
                        onchange="updateCartItem(<?php echo $row['pid']; ?>, this.value, document.getElementById('size-<?php echo $row['pid']; ?>').value)">
                </td>
                
                <!-- Size Selection -->
                <td>
                    <?php $currentSize = $row['size'] ?? ''; ?>
                    <select id="size-<?php echo $row['pid']; ?>" class="size-select" 
                            onchange="updateCartItem(<?php echo $row['pid']; ?>, document.getElementById('quantity-<?php echo $row['pid']; ?>').value, this.value)">
                        <option value="S" <?php if ($currentSize == 'S') echo 'selected'; ?>>Small</option>
                        <option value="M" <?php if ($currentSize == 'M') echo 'selected'; ?>>Medium</option>
                        <option value="L" <?php if ($currentSize == 'L') echo 'selected'; ?>>Large</option>
                        <option value="XL" <?php if ($currentSize == 'XL') echo 'selected'; ?>>Extra Large</option>
                    </select>
                </td>

                <!-- Actions -->
                <td>
                <button class="update-btn" onclick="updateCart(<?php echo $row['pid']; ?>)">Update</button>

                    <button class="remove-btn" onclick="removeFromCart(<?php echo $row['pid']; ?>)">Remove</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>


<div align="center">
    
<a href="checkout.php" class="cart" >PLACE ORDER<i class="fa-solid fa-cart-shopping"></i></a>
</div>

<script>
function updateCart(pid) {
    var quantity = document.getElementById("quantity-" + pid).value;
    var size = document.getElementById("size-" + pid).value;

    console.log("Updating cart for Product ID:", pid);
    console.log("Selected Quantity:", quantity);
    console.log("Selected Size:", size);

    if (!quantity || !size) {
        alert("Please select both quantity and size.");
        return;
    }

    fetch("update_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "pid=" + pid + "&quantity=" + quantity + "&size=" + size
    })
    .then(response => response.text())
    .then(data => {
        console.log("Server Response:", data);  // Log server response for debugging
        alert(data);
        location.reload();
    })
    .catch(error => console.error("Error updating cart:", error));
}


function removeFromCart(pid) {
    // Use fetch API to send an asynchronous POST request to remove_cart.php
    fetch("remove_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "pid=" + pid
    })
    .then(response => response.text())
    .then(data => {
        alert(data);  // Display server response (e.g., "Item removed from cart")
        location.reload();  // Reload the page to reflect updated cart
    })
    .catch(error => console.error("Error removing item from cart:", error));
}
</script>
<?php
include('db.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "Please log in to view your cart.";
    exit();
}

// Check for the latest "Pending" order for this user
$orderQuery = "SELECT * FROM orders WHERE user_id = ? AND order_status = 'Pending' ORDER BY expected_delivery DESC LIMIT 1";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if ($order) {
    // Display pending order details
    echo "<div class='order-status'>
            <p>Your recent order is currently <strong>Pending</strong>.</p>
            <p>Expected delivery date: <strong>" . htmlspecialchars($order['expected_delivery']) . "</strong></p>
          </div>";
} else {
    echo "<p>No pending orders.</p>";
}
?>
<div>
    
    <a href="myorders.php" class="cart" style="background-color:blue;"  >YOUR ORDERS</a>
    </div>
</body>  
</html>
<?php

$stmt->close();
$conn->close();

?>

