<?php
include('../includes/db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // First, fetch the image name to delete the file
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if ($product) {
        // Delete image from folder
        $imgPath = '../assets/images/' . $product['image'];
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Now delete from database
        $delete = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $delete->execute([$id]);
    }
}

header("Location: index.php");
exit;
