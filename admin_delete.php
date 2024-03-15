<?php
// admin_delete.php

include('config.php');

if (isset($_GET['id'])) {
    $productIdToDelete = $_GET['id'];

    $sql = "DELETE FROM products WHERE id = $productIdToDelete";
    $result = $conn->query($sql);

    if ($result) {
        header("Location: view_products.php");
        exit();
    } else {
        echo "<script>alert('Error deleting product');</script>";
    }
} else {
    echo "Invalid product ID";
}
?>
