<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

// Include database connection details
include_once "config.php"; // Replace with your actual database connection file

// Include the QR Code library
include_once "phpqrcode/qrlib.php"; // Make sure to download the library and adjust the path

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process the form data
    $productName = isset($_POST["product_name"]) ? $_POST["product_name"] : "";
    $productDescription = isset($_POST["product_description"]) ? $_POST["product_description"] : "";
    $quantity = isset($_POST["quantity"]) ? $_POST["quantity"] : 0;

    // Validate quantity
    if (!is_numeric($quantity) || $quantity <= 0) {
        echo "Invalid quantity value";
        exit();
    }

    // Insert product into the database
    $insertQuery = "INSERT INTO products (name, description, quantity) 
                    VALUES ('$productName', '$productDescription', '$quantity')";

    if (mysqli_query($conn, $insertQuery)) {
        // Get the last inserted product ID
        $productID = mysqli_insert_id($conn);

        // Generate QR code data
        $qrcodeData = "https://hamzalahrach.online/consult.php?product_id=$productID";
        
        // Generate QR code
        $qrcodeFileName = "qrcodes/qrcode_" . uniqid() . ".png";
        QRcode::png($qrcodeData, $qrcodeFileName);

        // Update the product entry with the QR code file name
        $updateQuery = "UPDATE products SET qrcode = '$qrcodeFileName' WHERE id = $productID";
        mysqli_query($conn, $updateQuery);

        // Redirect to the product list
        header("Location: list_product.php");
        exit();
    } else {
        // Error in product insertion
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit - Gestion de l'inventaire</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="https://hamzalahrach.online">Gestion de l'inventaire</a>
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

    <div class="container mt-4">
        <h2>Ajouter un nouveau produit</h2>

        <!-- Formulaire d'ajout de produit -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="product_name">Nom du produit :</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="form-group">
                <label for="product_description">Description du produit :</label>
                <textarea class="form-control" id="product_description" name="product_description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="quantity">Quantité :</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter le produit</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
