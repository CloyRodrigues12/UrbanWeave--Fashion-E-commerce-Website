<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="icon" href="Slogo.UrbanWeave.png" >
    <title>UrbanWeave - Admin Panel</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="Carousel.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fontawesome.com/icons/cart-shopping?f=classic&s=solid">

</head>
<body>

<div class="container">
    <div class="navbar">
        <div class="logo">
            <img src="Logo.UrbanWeave.png" alt="logo" height="60px">
        </div> &nbsp;
        <h2 style="color: rgb(71, 121, 104);">UrbanWeave</h2>
        <nav class="navb">
            <ul>
                <li class="<?= ($current_page == 'indexpg.php') ? 'active' : '' ?>"><a href="indexpg.php">Home</a></li>
                <li class="<?= ($current_page == 'productspg.php') ? 'active' : '' ?>"><a href="productspg.php">Products</a></li>
                <li class="<?= ($current_page == 'Men.php') ? 'active' : '' ?>"><a href="Men.php">Men</a></li>
                <li class="<?= ($current_page == 'Women.php') ? 'active' : '' ?>"><a href="Women.php">Women</a></li>
                <li class="<?= ($current_page == 'kids.php') ? 'active' : '' ?>"><a href="kids.php">Kids</a></li>
                <li class="<?= ($current_page == 'blogpg.php') ? 'active' : '' ?>"><a href="blogpg.php">Blog</a></li>
                <li class="<?= ($current_page == 'accountpg.php') ? 'active' : '' ?>"><a href="accountpg.php">Account</a></li>               
                <li class="<?= ($current_page == 'cartpg.php') ? 'active' : '' ?>"><a href="cartpg.php"><i class="fa fa-shopping-bag" aria-hidden="true"></i></a></li>
                <li class="active"><a href="DashBoardpg.php">Admin Dashboard</i></a></li>
            </ul>
        </nav>
    </div>
</div>

<br><br>
<h1 align="center">Admin Dashboard</h1>

<div class="adminbuttons">

<a href="users.php">USER Info</a> <br>
<a href="employee.php">Employee Info </a> <br>
<a href="products.php">ADD New Product</a> <br>
<a href="orders.php">Orders</a> <br>
<a href="logout.php">Logout</a>
</div>



</body>
</html>