<?php
session_start();

// Include database connection details
include_once "config.php";

// Initialize variables
$productID = 0;
$product_name = "";
$description = "";
$quantity = 0;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $productID = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

    // Debug information
    // echo "Debug - Product ID: $productID, Product Name: $product_name, Description: $description, Quantity: $quantity";

    // Validate and update the product
    if ($productID > 0 && !empty($product_name)) {
        // Update product details in the database
        $query = "UPDATE products SET name=?, description=?, quantity=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssii", $product_name, $description, $quantity, $productID);
            mysqli_stmt_execute($stmt);

            // Check if the update was successful
            if (mysqli_affected_rows($conn) > 0) {
                $success_message = "Product updated successfully!";
            } else {
                $error_message = "Failed to update the product.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Error in preparing the statement: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Invalid product data provided.";
    }
}

// Fetch existing product details
$query = "SELECT * FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $productID);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    // Check if the product exists
    if ($result && $product = mysqli_fetch_assoc($result)) {
        // Assign fetched values to variables
        $product_name = $product['name'];
        $description = $product['description'];
        $quantity = $product['quantity'];
    } else {
        // Handle the case where the product is not found
        $error_message = "Product not found for ID: " . $productID;
    }

    mysqli_stmt_close($stmt);
} else {
    $error_message = "Error in preparing the statement: " . mysqli_error($conn);
}

?>

<!-- ... (rest of the code remains unchanged) -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product - Inventory Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            text-align: center;
            padding-top: 60px;
        }

        nav {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>


    <div class="container">
        <h2>Modifier le produit</h2>
        <?php
        if (isset($success_message)) {
            echo "<div class='alert alert-success' role='alert'>$success_message</div>";
        } elseif (isset($error_message)) {
            echo "<div class='alert alert-danger' role='alert'>$error_message</div>";
        }
        ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="product_name">Nom du produit</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="quantity">Quantité</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $quantity; ?>" required>
            </div>
            <input type="hidden" name="product_id" value="<?php echo $productID; ?>">
            <button type="submit" class="btn btn-primary">Mettre à jour le produit</button>
        </form>
        <br>
        <a href="user_list_product.php" class="btn btn-secondary">Retour à la liste des produits</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
