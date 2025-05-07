<?php
include('../includes/db.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'];
    $category = $_POST['category'];


    if (!empty($name) && !empty($price) && $image['error'] === 0) {
        $imgName = uniqid() . '_' . basename($image['name']);
        $targetPath = '../assets/images/' . $imgName;

        if (move_uploaded_file($image['tmp_name'], $targetPath)) {
          $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, category) VALUES (?, ?, ?, ?, ?)");
          $stmt->execute([$name, $desc, $price, $imgName, $category]);          
            $message = "✅ Product added successfully!";
        } else {
            $message = "❌ Failed to upload image.";
        }
    } else {
        $message = "⚠️ Please fill all fields and upload a valid image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Add Product - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container py-4">
    <h2 class="mb-4">Add New Product</h2>
    <?php if ($message): ?>
    <div class="alert alert-info">
      <?= $message ?>
    </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category" class="form-select" required>
          <option value="">-- Select Category --</option>
          <option value="Dairy">Dairy</option>
          <option value="Vegetables">Vegetables</option>
          <option value="Fruits">Fruits</option>
          <option value="Snacks">Snacks</option>
          <option value="Beverages">Beverages</option>
          <option value="Household">Household</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Price (₹)</label>
        <input type="number" name="price" step="0.01" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
      </div>
      <button type="submit" class="btn btn-success">Add Product</button>
      <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
  </div>
</body>

</html>