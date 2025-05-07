<?php
include('../includes/db.php');

if (!isset($_GET['id'])) {
die("Product ID is required");
}

$id = $_GET['id'];
$product = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$product->execute([$id]);
$data = $product->fetch();

if (!$data) {
die("Product not found");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$name = $_POST['name'];
$desc = $_POST['description'];
$price = $_POST['price'];
$newImage = $_FILES['image'];
$category = $_POST['category'];


$imageName = $data['image'];


if ($newImage['error'] === 0) {
$imageName = uniqid() . '_' . basename($newImage['name']);
move_uploaded_file($newImage['tmp_name'], '../assets/images/' . $imageName);
}

$update = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ?, category = ? WHERE id = ?");
$update->execute([$name, $desc, $price, $imageName, $category, $id]);

$message = "✅ Product updated successfully!";
// Reload updated data
$product = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$product->execute([$id]);
$data = $product->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Product - Admin</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet">
    </head>
    <body>
        <div class="container py-4">
            <h2 class="mb-4">Edit Product</h2>
            <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control"
                        value="<?= htmlspecialchars($data['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                        class="form-control"><?= htmlspecialchars($data['description']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value>-- Select Category --</option>
                        <?php
                        $categories = ['Dairy', 'Vegetables', 'Fruits',
                        'Snacks', 'Beverages', 'Household'];
                        foreach ($categories as $cat) {
                        $selected = ($data['category'] === $cat) ? 'selected' :
                        '';
                        echo "<option value='$cat' $selected>$cat</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" name="price" step="0.01"
                        class="form-control" value="<?= $data['price'] ?>"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>
                    <img src="../assets/images/<?= $data['image'] ?>"
                        width="100" height="100" style="object-fit:cover;">
                </div>
                <div class="mb-3">
                    <label class="form-label">New Image (optional)</label>
                    <input type="file" name="image" class="form-control"
                        accept="image/*">
                </div>
                <button type="submit" class="btn btn-success">Update
                    Product</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </body>
</html>
