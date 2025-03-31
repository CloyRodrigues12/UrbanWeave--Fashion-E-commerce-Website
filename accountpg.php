<?php
session_start(); // Start the session
include 'db.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    // Query to get user information
    $stmt = $conn->prepare("SELECT first_name,last_name,email, role FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($first_name,$last_name,$email, $role);
    $stmt->fetch();
    $stmt->close();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="icon" href="Slogo.UrbanWeave.png" >
    <title>UrbanWeave</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);

            height: 100vh;
        }

        .account-container {
            max-width: 600px;
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            margin-left: 30%;
        }

        h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            font-size: 16px;
        }

        .cart {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s;
            margin-top: 15px;
        }

        .cart:hover {
            background-color: orange;
            transform: translateY(-2px);
        }

        .logout-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #ff1a1a;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>
<br><br>
<div class="account-container" >
    <h2>Welcome, <?php echo htmlspecialchars($first_name); ?></h2>
    <p><strong><?php echo htmlspecialchars($first_name); ?></strong> <strong><?php echo htmlspecialchars($last_name); ?></strong></p>
    <p><?php echo htmlspecialchars($email); ?></p>
    <br>
    <p>You are logged in as <strong><?php echo htmlspecialchars($role); ?></strong>.</p><br><br>
    <form action="logout.php" method="POST">
        <button type="submit" name="logout" class="logout-btn">Logout</button>
    </form>

    <div align="center">
        <a href="myorders.php" class="cart" style="background-color:blue;">YOUR ORDERS</a>
    </div>
</div>


</body>
</html>
<?php
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="styles.css">

    </head>
    <body class="background">
        <div class="login-container" >
            <h2>Login</h2>

            <!-- Display any login error messages -->
            <?php
            if (isset($_SESSION['login_error'])) {
                echo "<div class='error-message'>" . $_SESSION['login_error'] . "</div>";
                unset($_SESSION['login_error']); // Remove the error message after displaying it
            }
            ?>

            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
            <p>Don't have an account? <a href="registerpg.php">Register here</a></p>
        </div>
    </body>
    </html>
    <?php
}
?>

