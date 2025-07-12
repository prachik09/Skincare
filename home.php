<?php
session_start();
require 'config.php';

// Check if the user is logged in and determine if they are an admin
$isLoggedIn = isset($_SESSION['user']);
$isAdmin = $isLoggedIn && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';

// Fetch products from the database
$products = [];
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $productName = $_POST['product_name'];
    if (isset($_SESSION['cart'][$productName])) {
        $_SESSION['cart'][$productName]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$productName] = ['quantity' => 1];
    }
    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Group products by brand
$brands = [];
foreach ($products as $product) {
    $brands[$product['brand']][] = $product;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GLOW GUIDE</title>
    
    <style>
         body {
            font-family: Modern No. 20;
            background-image:url(bg.jpg);
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #0b1957;
            color: white;
            padding: 15px;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
            align-items: center;
    height: 200px; /* Increase the height of the header */ 
    background-size: cover; /* Ensure the background image covers the header */
    background-position: center; /* Position the image in the center */
    background-repeat: no-repeat
        }
        .header h1 {
            margin: 0;
            flex: 1;
            text-align: center;
            font-family: 'Modern No. 20', bold; /* Custom font */
            font-size: 70px; 
        }
        .header .buttons {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header .buttons a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            background-color: #007BFF;
            border-radius: 5px;
            font-weight: bold;
        }
        .header .buttons a:hover {
            background-color: #0056b3;
        }
        .container {
            width: 90%;
            margin: 30px auto;
        }
        .brand-section {
            margin-bottom: 40px;
        }

        .brand-section h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #333;
        }

        .product-list {
            display: flex;
            overflow-x: auto;
            padding-bottom: 15px;
        }

        .product-list::-webkit-scrollbar {
            height: 8px;
        }

        .product-list::-webkit-scrollbar-thumb {
            background-color: #bbb;
            border-radius: 10px;
        }

        .product {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
            flex: 0 0 auto;
            width: 200px;
        }

        .product img {
            max-width: 90%;
            height: 200px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .product h2 {
            margin-top: 0;
            font-size: 18px;
        }

        .product p {
            color: #555;
        }

        .product .price {
            color: black;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product .tags {
            margin-top: 10px;
        }

        .product .tag {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #0b1957;
            color: white;
            border-radius: 3px;
            font-size: 12px;
        }

        .quiz-link {
            display: block;
            text-align: center;
            margin-top: 50px;
            padding: 10px;
            background-color: #0b1957;
            color:white;
            text-decoration: none;
            border-radius: 5px;
            width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .quiz-link:hover {
            background-color: #0b1957;
        }

        
        .add-to-cart-button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 14px;
        }

        .add-to-cart-button:hover {
            background-color: #218838;
        }

    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1>GLOW GUIDE</h1>
        <div class="buttons">
            <button class="cart-button" onclick="location.href='cart.php';">
                <i class="fas fa-shopping-bag"></i>
            </button>
            <?php if ($isLoggedIn): ?>
                <a href="admin login.php">Profile</a>
                <?php if ($isAdmin): ?>
                    <a href="dashboard.php">Admin Dashboard</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <a href="quiz.php" class="quiz-link">Take Skin Quiz >>></a>
        <h2>Featured Products by Brand</h2>
        
        <?php foreach ($brands as $brand => $brandProducts): ?>
            <div class="brand-section">
                <h3><?php echo htmlspecialchars($brand); ?></h3>
                <div class="product-list">
                    <?php foreach ($brandProducts as $product): ?>
                        <div class="product">

                            <img src="uploads/<?php echo htmlspecialchars(basename($product['image'])); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 style="max-width: 100%; height: 200px; border-radius: 8px;">
                            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p class="price">₹<?php echo htmlspecialchars($product['price']); ?></p>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="tags">
                                <?php
                                $tags = explode(',', $product['tags']);
                                foreach ($tags as $tag): ?>
                                    <span class="tag"><?php echo htmlspecialchars(trim($tag)); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <form method="post">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                <input type="hidden" name="add_to_cart" value="1">
                                <button type="submit" class="add-to-cart-button">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
