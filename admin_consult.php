<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

// Include database connection details
include_once "config.php"; // Replace with your actual database connection file

// Check if the product ID is provided in the URL
if (!isset($_GET['product_id'])) {
    header("Location: list_product.php"); // Redirect to the product list if no ID is provided
    exit();
}

// Sanitize the input to prevent SQL injection
$productID = mysqli_real_escape_string($conn, $_GET['product_id']);

// Fetch product details from the database
$query = "SELECT * FROM products WHERE id = $productID";
$result = mysqli_query($conn, $query);

// Check if the product is found
if (!$result || mysqli_num_rows($result) == 0) {
    echo "Product not found.";
    exit();
}

// Fetch and display product details
$product = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Consultation - Inventory Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            text-align: center;
            padding-top: 60px; /* Adjusted top padding for fixed navbar */
        }

        nav {
            background-color: #f8f9fa; /* Added background color for navbar */
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .container {
            margin-top: 20px;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <a class="navbar-brand" href="#">
            <img src="somaport-removebg-preview.png" alt="Logo" width="60%" height="50%">
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">Paramètres admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Consultation de produit</h2>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th scope="row">Identifiant du produit</th>
                    <td><?php echo $product['id']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Nom du produit</th>
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
                    <td><img src='<?php echo $product['qrcode']; ?>' style='width:30%;' alt='Code QR'></td>
                </tr>
            </tbody>
        </table>
        <button class="btn btn-primary print-button" onclick="printPage()">Imprimer</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function printPage() {
            // Masquer le bouton avant l'impression
            document.querySelector('.print-button').style.display = 'none';
            window.print();
        }
    </script>
</body>


</html>
