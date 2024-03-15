<?php
// update_product.php

include('config.php');

// Function to update a product
function updateProduct($productId, $name, $description, $quantity) {
    global $conn;
    $name = mysqli_real_escape_string($conn, $name);
    $description = mysqli_real_escape_string($conn, $description);

    $sql = "UPDATE products SET name='$name', description='$description', quantity=$quantity WHERE id=$productId";
    $result = $conn->query($sql);
    return $result;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];
    $productQuantity = $_POST['product_quantity'];

    if (updateProduct($productId, $productName, $productDescription, $productQuantity)) {
        header("Location: view_products.php");
        exit();
    } else {
        echo "<script>alert('Error updating product');</script>";
    }
}

// Retrieve product information
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id=$productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $productName = $row['name'];
        $productDescription = $row['description'];
        $productQuantity = $row['quantity'];
    } else {
        echo "<script>alert('Product not found');</script>";
        header("Location: view_products.php");
        exit();
    }
} else {
    echo "<script>alert('Product ID not provided');</script>";
    header("Location: view_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product - Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le produit - Gestion des stocks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Navbar (You can reuse the navbar from the dashboard) -->

    <div class="container mt-4">
        <h2 class="text-center">Modifier le produit</h2>

        <form action="" method="post">
            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">

            <div class="form-group">
                <label for="product_name">Nom :</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $productName; ?>" required>
            </div>

            <div class="form-group">
                <label for="product_description">Description :</label>
                <textarea class="form-control" id="product_description" name="product_description" rows="3"><?php echo $productDescription; ?></textarea>
            </div>

            <div class="form-group">
                <label for="product_quantity">Quantité :</label>
                <input type="number" class="form-control" id="product_quantity" name="product_quantity" value="<?php echo $productQuantity; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Modifier le produit</button>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>


</html>
