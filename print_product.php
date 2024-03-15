<?php
// print_product.php

include('config.php');
include('qrlib.php'); // Include the qrlib.php library

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product details based on the provided ID
    $sql = "SELECT * FROM products WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Generate QR code
        $qrCodeContent = "Product ID: " . $product['id'] . "\nName: " . $product['name'] . "\nDescription: " . $product['description'] . "\nQuantity: " . $product['quantity'];
        $qrCodePath = 'qrcodes/product_' . $product['id'] . '.png';
        QRcode::png($qrCodeContent, $qrCodePath, QR_ECLEVEL_L, 10); // Adjust error correction level and size as needed
    } else {
        echo "Product not found";
        exit();
    }
} else {
    echo "Invalid product ID";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Product - Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add any additional styles you need -->
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Product Details</h2>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                <p class="card-text">Description: <?php echo $product['description']; ?></p>
                <p class="card-text">Quantity: <?php echo $product['quantity']; ?></p>
                
                <!-- Display the QR code -->
                <img src="<?php echo $qrCodePath; ?>" alt="QR Code" class="img-fluid">

                <!-- Add any other details you want to display -->

                <button onclick="window.print()" class="btn btn-primary">Print</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
