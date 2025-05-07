<?php
include('includes/db.php');

// Handle category filter
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM products";
$params = [];

if (!empty($category)) {
    $query .= " WHERE category = ?";
    $params[] = $category;
}

if (!empty($search)) {
    $query .= empty($params) ? " WHERE" : " AND";
    $query .= " name LIKE ?";
    $params[] = '%' . $search . '%';
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>QuickBasket - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .product-card img {
      max-height: 180px;
      object-fit: contain;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">
        <img src="assets/images/logo.png" alt="QuickBasket" width="60"> QuickBasket
      </a>

      <form class="d-flex me-auto ms-3" method="GET">
        <input class="form-control me-2" type="search" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>

      <div class="d-flex align-items-center">
        <a href="#" class="btn btn-outline-secondary me-2">👤</a>
        <a href="#" class="btn btn-success">🛒 Cart</a>
      </div>
    </div>
  </nav>

  <!-- Category Filters -->
  <div class="container my-4">
    <div class="d-flex flex-wrap gap-2 justify-content-center">
      <?php
      $categories = ['All', 'Dairy', 'Vegetables', 'Fruits', 'Snacks', 'Beverages', 'Household'];
      foreach ($categories as $cat) {
        $active = ($cat === 'All' && empty($category)) || $category === $cat;
        $catLink = $cat === 'All' ? 'index.php' : "index.php?category=" . urlencode($cat);
        echo "<a href='$catLink' class='btn btn-outline-primary " . ($active ? 'active' : '') . "'>$cat</a>";
      }
      ?>
    </div>
  </div>

  <!-- Product Grid -->
  <div class="container">
    <div class="row g-4">
      <?php if ($products): ?>
        <?php foreach ($products as $product): ?>
          <div class="col-md-3">
            <div class="card product-card shadow-sm h-100">
              <img src="assets/images/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                <p class="card-text fw-bold text-success">₹<?= $product['price'] ?></p>
                <button class="btn btn-sm btn-outline-primary">Add to Cart</button>
              </div>
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
