<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: user/login.php"); // or the correct path to your login page
  exit;
}

include('includes/db.php');

// Handle item removal
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

// Update quantities
if (isset($_POST['update_qty'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty > 0 && isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = (int)$qty;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
    header("Location: cart.php");
    exit;
}

$cart_items = $_SESSION['cart'] ?? [];
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
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Product</th>
            <th>Image</th>
            <th>Price (₹)</th>
            <th>Quantity</th>
            <th>Subtotal (₹)</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($cart_items as $id => $item): 
            // Validate structure
            if (!isset($item['quantity'])) continue;

            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();

            if (!$product) continue;

            $quantity = (int)$item['quantity'];
            $item_total = $quantity * $product['price'];
            $total += $item_total;
        ?>
          <tr>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><img src="assets/images/<?= htmlspecialchars($product['image']) ?>" alt="" style="width: 60px;"></td>
            <td>₹<?= number_format($product['price'], 2) ?></td>
            <td>
              <input type="number" name="qty[<?= $id ?>]" value="<?= $quantity ?>" min="1" class="form-control" style="width: 80px;">
            </td>
            <td>₹<?= number_format($item_total, 2) ?></td>
            <td>
              <a href="?remove=<?= $id ?>" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>

      <div class="d-flex justify-content-between align-items-center">
        <div>
          <button type="submit" name="update_qty" class="btn btn-primary">
            <i class="bi bi-arrow-repeat"></i> Update Quantities
          </button>
          <a href="index.php" class="btn btn-secondary">← Continue Shopping</a>
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
