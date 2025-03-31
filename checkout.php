<?php
session_start();
include('db.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "Please log in to view your cart.";
    exit();
}

// Fetch cart items
$query = "SELECT c.*, p.pname, p.pcategory, p.pimg1, p.price, c.size 
        FROM cart AS c 
        JOIN products AS p ON c.pid = p.pid 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch saved addresses
$query_addresses = "SELECT * FROM user_addresses WHERE user_id = ?";
$stmt_addresses = $conn->prepare($query_addresses);
$stmt_addresses->bind_param("i", $user_id);
$stmt_addresses->execute();
$address_result = $stmt_addresses->get_result();

// Get total price of cart items
$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $total_price += $row['price'] * $row['quantity'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://fontawesome.com/icons/money-check?f=classic&s=light">
<title>Checkout</title>
<style>

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f9f9f9;
        margin: 0;
    }
    h2 {
        text-align: center;
    }
    .container1 {
        max-width: 95%;
        padding: 8px;
        margin: 0 auto;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    .address-form, .cart-summary {
        margin-bottom: 30px;
    }
    .cart-summary table {
        width: 100%;
        border-collapse: collapse;
    }
    .cart-summary th, .cart-summary td {
        padding: 4px;
        text-align: center;
        border: 1px solid #ddd;
    }
    .cart-summary th {
        background-color: #4CAF50;
        color: white;
    }
    .form-control {
        width: 100%;
        padding: 8px;
        margin: 5px 0;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .button {
        display: inline-block;
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .button:hover {
        background-color: orange;
    }
    .pimg img{
        height:150px;
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

<?php include 'navbar.php'; ?>
<br>
<div class="container1">
    <h2>Checkout</h2> <br>

    <!-- Cart Summary -->
    <div class="cart-summary">
        <h3>Order Summary</h3>
        <table>
            <tr>
                <th></th>
                <th>Product</th>
                <th>Price</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php
            $result->data_seek(0); // Reset result pointer to start
            while ($row = $result->fetch_assoc()) :
                $item_total = $row['price'] * $row['quantity'];
            ?>
            <tr>
            <td class="pimg"><img  src="uploads/<?php echo htmlspecialchars($row['pimg1']); ?>" alt=""></td>
                <td><?php echo htmlspecialchars($row['pname']); ?></td>
                <td>Rs <?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo htmlspecialchars($row['size']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td>Rs <?php echo $item_total; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <div style="text-align: right; font-size: 18px; font-weight: bold;">Total: Rs <?php echo $total_price; ?></div>
    </div>

    <!-- Address Form -->
    <div class="address-form">
        <h3>Delivery Address</h3>
        <form action="place_order.php" method="POST">
            <label for="address">Select Address</label>
            <select id="address" name="address_id" class="form-control" required>
                <option value="">Select a saved address</option>
                <?php while ($address = $address_result->fetch_assoc()) : ?>
                    <option value="<?php echo $address['address_id']; ?>"><?php echo htmlspecialchars($address['street'] . ", " . $address['city'] . ", " . $address['state']); ?></option>
                <?php endwhile; ?>
                <option value="new">Add a new address</option>
            </select>

<!-- New Address Form (Hidden by Default) -->
<div id="new-address-form" style="display: none;">
    <input type="text" name="street" id="street" class="form-control" placeholder="Street" required>
    <input type="text" name="city" id="city" class="form-control" placeholder="City" required>
    <input type="text" name="state" id="state" class="form-control" placeholder="State" required>
    <input type="text" name="postal_code" id="postal_code" class="form-control" placeholder="Postal Code" required>
    <input type="text" name="country" id="country" class="form-control" placeholder="Country" required>
    <button type="button" id="save-address-btn" class="button">Save Address</button>
</div>
<div align="center">
    <a href="#" id="proceed-payment" class="cart">Proceed with Payment &nbsp; <i class="fa-regular fa-credit-card"></i></a>
</div>
<script>
document.getElementById("address").addEventListener("change", function() {
    var newAddressForm = document.getElementById("new-address-form");
    if (this.value === "new") {
        newAddressForm.style.display = "block";
    } else {
        newAddressForm.style.display = "none";
    }
});

document.getElementById("save-address-btn").addEventListener("click", function() {
    // Get the values from the new address form
    var street = document.getElementById("street").value;
    var city = document.getElementById("city").value;
    var state = document.getElementById("state").value;
    var postal_code = document.getElementById("postal_code").value;
    var country = document.getElementById("country").value;

    // Send the data via AJAX to save_address.php
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_address.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText);  // Show response message (success or error)
            if (xhr.responseText === "Address saved successfully!") {
                // Optionally, you can refresh the page or update the address dropdown here
                location.reload();
            }
        }
    };

    xhr.send("street=" + encodeURIComponent(street) +
            "&city=" + encodeURIComponent(city) +
            "&state=" + encodeURIComponent(state) +
            "&postal_code=" + encodeURIComponent(postal_code) +
            "&country=" + encodeURIComponent(country));
});
</script>
<script>
    document.getElementById("proceed-payment").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default link behavior

        var addressSelect = document.getElementById("address");
        if (addressSelect.value === "" || addressSelect.value === "new") {
            alert("Please select a delivery address to proceed with payment.");
        } else {
            window.location.href = "payment.php"; // Redirect to payment page
        }
    });
</script>


</body>
</html>

<?php
$stmt->close();
$stmt_addresses->close();
$conn->close();
?>
