<?php
session_start();
include('includes/db.php');

// Handle removal
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
}

// Update quantity
if (isset($_POST['update_qty'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty > 0) {
            $_SESSION['cart'][$id] = $qty;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Cart - QuickBasket</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<div class="container py-5">
  <h2 class="mb-4">🛒 Your Shopping Cart</h2>

  <?php if (empty($cart_items)): ?>
    <div class="alert alert-info">Your cart is empty.</div>
  <?php else: ?>
    <form method="POST">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Product</th>
            <th>Image</th>
            <th>Price (₹)</th>
            <th>Quantity</th>
            <th>Total (₹)</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody>
        <?php
        foreach ($cart_items as $id => $qty):
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();

            if (!$product) continue;

            $item_total = $product['price'] * $qty;
            $total += $item_total;
        ?>
          <tr>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><img src="assets/images/<?= $product['image'] ?>" alt="" style="width: 60px;"></td>
            <td><?= number_format($product['price'], 2) ?></td>
            <td>
              <input type="number" name="qty[<?= $id ?>]" value="<?= $qty ?>" min="1" class="form-control" style="width:80px;">
            </td>
            <td><?= number_format($item_total, 2) ?></td>
            <td><a href="?remove=<?= $id ?>" class="btn btn-danger btn-sm">❌</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>

      <div class="d-flex justify-content-between">
        <div>
          <button type="submit" name="update_qty" class="btn btn-primary">Update Quantities</button>
          <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
        </div>
        <div>
          <h4>Total: ₹<?= number_format($total, 2) ?></h4>
          <button class="btn btn-success" disabled>Proceed to Checkout</button>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>

</body>
</html>
