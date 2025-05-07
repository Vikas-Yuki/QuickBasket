<?php
include('../includes/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Manage Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container py-4">
    <h2 class="mb-4">Admin - Product List</h2>
    <a href="add_product.php" class="btn btn-primary mb-3">Add Product</a>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Price</th>
          <th>Image</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stmt = $pdo->query("SELECT * FROM products");
        $count = 1;
        while ($row = $stmt->fetch()):
        ?>
        <tr>
          <td><?= $count++ ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td>₹<?= number_format($row['price'], 2) ?></td>
          <td><img src="../assets/images/<?= $row['image'] ?>" width="80" height="80" style="object-fit:cover;"></td>
          <td>
            <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
