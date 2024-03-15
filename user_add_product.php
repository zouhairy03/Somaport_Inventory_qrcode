<?php
session_start();

// Check if the user is logged in

// Include database connection details
include_once "config.php"; // Replace with your actual database connection file

// Include the QR Code library
include_once "phpqrcode/qrlib.php"; // Make sure to download the library and adjust the path

// Initialize variables for form data and errors
$productName = $productDescription = $quantity = "";
$productNameErr = $quantityErr = $errorMsg = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_product_trigger"])) {
    // Process the form data
    $productName = isset($_POST["product_name"]) ? htmlspecialchars(trim($_POST["product_name"])) : "";
    $productDescription = isset($_POST["product_description"]) ? htmlspecialchars(trim($_POST["product_description"])) : "";
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 0;

    // Validate inputs
    if (empty($productName)) {
        $productNameErr = "Product name is required";
    }

    if ($quantity <= 0) {
        $quantityErr = "Quantity must be greater than zero";
    }

    // If there are no errors, proceed with database operations
    if (empty($productNameErr) && empty($quantityErr)) {
        // Insert product into the database (use prepared statement to prevent SQL injection)
        $insertQuery = "INSERT INTO products (name, description, quantity) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssi", $productName, $productDescription, $quantity);

            if (mysqli_stmt_execute($stmt)) {
                // Get the last inserted product ID
                $productID = mysqli_insert_id($conn);

                // Generate QR code data (replace with your desired data)
                $qrcodeData = "Product ID: $productID\n";
                $qrcodeData .= "Name: $productName\n";
                $qrcodeData .= "Description: $productDescription\n";
                $qrcodeData .= "Quantity: $quantity";

                // Generate QR code
                $qrcodeFileName = "qrcodes/qrcode_" . uniqid() . ".png";
                QRcode::png($qrcodeData, $qrcodeFileName);

                // Update the product entry with the QR code file name
                $updateQuery = "UPDATE products SET qrcode = ? WHERE id = ?";
                $stmtUpdate = mysqli_prepare($conn, $updateQuery);
                mysqli_stmt_bind_param($stmtUpdate, "si", $qrcodeFileName, $productID);
                mysqli_stmt_execute($stmtUpdate);

                // Redirect to the product list
                header("Location: user_list_product.php");
                exit();
            } else {
                // Error in product insertion
                $errorMsg = "Error: " . mysqli_error($conn);
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            // Error in preparing the statement
            $errorMsg = "Error: " . mysqli_error($conn);
        }

        // Close the database connection
        mysqli_close($conn);
    }
}
?>
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Inventory Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            text-align: center;
        }

        @media (max-width: 576px) {
            .navbar-toggler {
color: black;
            }

            .navbar-toggler-icon {
                background-color: #fff;
            }


            .navbar-nav .nav-link {
                color: black !important;
            }

            .navbar-nav .dropdown-menu {
                background-color: #79ac31; /* Logo Green */
            }

            .navbar-nav .dropdown-item {
                color: #fff !important;
            }
        }
    </style>
</head>


<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="somaport-removebg-preview.png" alt="Logo" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="user_settings.php">Paramètres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </nav>

    <br>

    <div class="container">
        <h2>Ajouter un nouveau produit</h2>

        <!-- Afficher un message d'erreur -->
        <?php if (!empty($errorMsg)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMsg; ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'ajout de produit -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="product_name">Nom du produit :</label>
                <input type="text" class="form-control" id="product_name" name="product_name"
                    value="<?php echo htmlspecialchars($productName); ?>" required>
                <span class="text-danger"><?php echo $productNameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="product_description">Description du produit :</label>
                <textarea class="form-control" id="product_description" name="product_description"
                    rows="3"><?php echo htmlspecialchars($productDescription); ?></textarea>
            </div>
            <div class="form-group">
                <label for="quantity">Quantité :</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $quantity; ?>"
                    required>
                <span class="text-danger"><?php echo $quantityErr; ?></span>
            </div>
            <input type="hidden" name="add_product_trigger" value="1">
            <button type="submit" class="btn btn-primary">Ajouter le produit</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

