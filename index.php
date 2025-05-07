<?php
include './includes/db.php';

$category = isset($_GET['category']) ? $_GET['category'] : 'All';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build the SQL query
$sql = "SELECT * FROM products";
$params = [];

if ($category !== 'All') {
    $sql .= " WHERE category = :category";
    $params[':category'] = $category;
}

if ($search !== '') {
    if (strpos($sql, 'WHERE') !== false) {
        $sql .= " AND product_name LIKE :search";
    } else {
        $sql .= " WHERE product_name LIKE :search";
    }
    $params[':search'] = "%$search%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>QuickBasket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            height: 100%;
        }
        .product-card img {
            max-height: 150px;
            object-fit: contain;
        }
        .price {
            color: green;
            font-weight: bold;
        }
        .category-btns .btn {
            margin: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light bg-light p-3">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="./assets/images/logo.png" alt="" width="40" height="40" class="d-inline-block align-text-top me-2">
                <strong>QuickBasket</strong>
            </a>
            <form class="d-flex" method="get">
                <input class="form-control me-2" type="search" placeholder="Search products..." name="search" value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <div>
                <a href="#" class="btn btn-outline-secondary me-2"><i class="bi bi-person"></i></a>
                <a href="cart.php" class="btn btn-success"><i class="bi bi-cart"></i> Cart</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="category-btns text-center">
            <?php
            $categories = ['All', 'Dairy', 'Vegetables', 'Fruits', 'Snacks', 'Beverages', 'Household'];
            foreach ($categories as $cat) {
                $active = ($category === $cat) ? 'btn-primary' : 'btn-outline-primary';
                echo "<a href='?category=$cat' class='btn $active'>$cat</a>";
            }
            ?>
        </div>

        <div class="row mt-4">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-3 mb-4">
                        <div class="product-card h-100">
                            <img src="../QuickBasket/assets/images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" >
                            <h5><?= htmlspecialchars($product['name']) ?></h5>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                            <p class="price">₹<?= htmlspecialchars($product['price']) ?></p>
                            <form method="POST" action="./user/add_to_cart.php">
  <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
  <button type="submit" class="btn btn-primary">Add to Cart</button>
</form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No products found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
