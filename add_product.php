<?php
// add_product.php

include('config.php');
include('phpqrcode/qrlib.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = (int)$_POST['quantity'];

    // Generate a unique filename for the QR code (e.g., product_id.png)
    $qrcodeFilename = 'qrcodes/qrcode_' . uniqid() . '.png';

    // Combine product information into a string to be encoded in the QR code
    $qrcodeContent = "Name: $name\nDescription: $description\nQuantity: $quantity";

    // Insert the new product into the database
    $sql = "INSERT INTO products (name, description, quantity, qrcode) VALUES ('$name', '$description', $quantity, '$qrcodeFilename')";

    if ($conn->query($sql) === TRUE) {
        // Generate and save the QR code image
        QRcode::png($qrcodeContent, $qrcodeFilename, QR_ECLEVEL_L, 5);

        // Redirect to the view_products.php page after successful insertion
        header("Location: view_products.php");
        exit();
    } else {
        // Handle errors (e.g., display an error message)
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// If the form is not submitted or there is an error, continue to display the form
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit - Tableau de bord administrateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Ajoutez les styles supplémentaires dont vous avez besoin -->
</head>

<body>
    <!-- Navbar (Vous pouvez réutiliser la barre de navigation du tableau de bord) -->

    <div class="container mt-4">
        <h2 class="text-center">Ajouter un nouveau produit</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="name">Nom du produit</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="quantity">Quantité</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>

            <!-- Vous pouvez ajouter d'autres champs au besoin -->

            <button type="submit" class="btn btn-primary">Ajouter le produit</button>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
