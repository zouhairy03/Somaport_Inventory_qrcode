<?php
// consult_product.php

include('config.php');

// Check if the product ID is provided
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product details from the database
    $sql = "SELECT * FROM products WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        // Redirect to the view_products.php page if the product is not found
        header("Location: view_products.php");
        exit();
    }
} else {
    // Redirect to the view_products.php page if no product ID is provided
    header("Location: view_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consult Product - Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add any additional styles you need -->
</head>
<body>
    <style>
        body {
            text-align: center;
        }
    </style>
    <!-- Navbar (You can reuse the navbar from the dashboard) -->

    <div class="container mt-4">
        <!-- Logo Row -->
        <div class="row mb-4">
            <div class="col text-center">
                <!-- Add your logo image source here -->
                <img src="somaport-removebg-preview.png" style="width: 10%;" alt="Logo" class="img-fluid">
            </div>
        </div>

        <h2 class="text-center">Consultation de Produit</h2>

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th scope="row">ID du produit</th>
                    <td><?php echo $product['id']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Nom</th>
                    <td><?php echo $product['name']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Description</th>
                    <td><?php echo $product['description']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Quantité</th>
                    <td><?php echo $product['quantity']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Code QR</th>
                    <td><img src="<?php echo $product['qrcode']; ?>" alt="Code QR" class="img-fluid"></td>
                </tr>
            </tbody>
        </table>

        <div class="mt-3">
            <a href="view_products.php" class="btn btn-secondary">Retour aux Produits</a>
            <button class="btn btn-primary ml-2" onclick="printProduct()">Imprimer</button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function printProduct() {
            // Hide the print and back buttons before printing
            document.querySelector('.btn-primary').style.display = 'none';
            document.querySelector('.btn-secondary').style.display = 'none';

            // Print the product details
            window.print();

            // Show the print and back buttons after printing
            document.querySelector('.btn-primary').style.display = 'inline';
            document.querySelector('.btn-secondary').style.display = 'inline';
        }
    </script>
</body>

</html>
