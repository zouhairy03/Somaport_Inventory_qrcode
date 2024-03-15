<?php
session_start();

include_once "config.php";

$productID = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

$query = "SELECT * FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    echo "Error in preparing the statement: " . mysqli_error($conn);
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $productID);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_error($stmt)) {
    echo "Error in executing the statement: " . mysqli_stmt_error($stmt);
    exit();
}

$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo "Error in getting result set: " . mysqli_error($conn);
    exit();
}

$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Somaport Consultation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            text-align: center;
            padding-top: 60px;
        }

        nav {
            background-color: #f8f9fa;
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

    <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="somaport-removebg-preview.png" alt="Logo" width="100%" height="70%">
        </a>
    </nav>

    <div class="container">
        <h2>Consultation de Produit</h2>
        <br>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th scope="row">Identifiant du Produit</th>
                    <td><?php echo $product['id']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Nom du Produit</th>
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

        <button class="btn btn-primary" onclick="printPage()">Imprimer</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function printPage() {
            // Masquer le bouton d'impression
            document.querySelector('.btn-primary').style.display = 'none';
            
            // Sauvegarder l'URL actuelle
            var currentUrl = window.location.href;
            
            // Modifier l'URL sans paramètres de requête
            history.pushState({}, document.title, window.location.pathname);
            
            // Imprimer la page
            window.print();
            
            // Restaurer l'URL d'origine
            history.pushState({}, document.title, currentUrl);
        }
    </script>
</body>

</html>
