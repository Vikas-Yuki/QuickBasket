<?php
include('../includes/db.php');

// Fetch products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by category
$grouped = [];
foreach ($products as $prod) {
    $grouped[$prod['category']][] = $prod;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>QuickBasket - Grocery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f9f9f9;
    }
    .navbar {
      margin-bottom: 30px;
    }
    .product-card {
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
      border-radius: 10px;
    }
    .product-card img {
      height: 180px;
      object-fit: cover;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }
    .section-title {
      margin: 30px 0 15px;
      border-bottom: 2px solid #ccc;
      padding-bottom: 5px;
      font-weight: bold;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="../assets/images/Quick Basket2.png" alt="QuickBasket" width="30" height="30" class="d-inline-block align-text-top">
      QuickBasket
    </a>
    <form class="d-flex ms-auto me-3">
      <input class="form-control me-2" type="search" placeholder="Search groceries..." aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
    <a href="cart.php" class="btn btn-outline-primary me-2">Cart 🛒</a>
    <a href="#" class="btn btn-outline-secondary">Profile 👤</a>
  </div>
</nav>

<div class="container">
  <?php foreach ($grouped as $category => $items): ?>
    <h4 class="section-title"><?= htmlspecialchars($category) ?></h4>
    <div class="row">
      <?php foreach ($items as $product): ?>
        <div class="col-md-3 mb-4">
          <div class="card product-card">
            <img src="../assets/images/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
              <p><strong>₹<?= htmlspecialchars($product['price']) ?></strong></p>
              <form method="POST" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" name="product_name" value="<?= $product['name'] ?>">
                <input type="hidden" name="product_price" value="<?= $product['price'] ?>">
                <input type="hidden" name="product_image" value="<?= $product['image'] ?>">
                <button type="submit" class="btn btn-primary w-100">Add to Cart 🛒</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>
