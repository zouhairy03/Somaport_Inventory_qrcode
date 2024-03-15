<?php
session_start();

include_once "config.php";

// Check if a search term is provided
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Define the number of products per page
$productsPerPage = 10;

// Determine the current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $productsPerPage;

// Modify the query to include search, LIMIT, and OFFSET for pagination
$query = "SELECT * FROM products WHERE name LIKE '%$searchTerm%' ORDER BY id DESC LIMIT $productsPerPage OFFSET $offset";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}

// Count total number of products (ignoring LIMIT for pagination)
$totalProductsQuery = "SELECT COUNT(*) AS total FROM products WHERE name LIKE '%$searchTerm%'";
$totalProductsResult = mysqli_query($conn, $totalProductsQuery);
$totalProductsRow = mysqli_fetch_assoc($totalProductsResult);
$totalProducts = $totalProductsRow['total'];

// Calculate the total number of pages
$totalPages = ceil($totalProducts / $productsPerPage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List - Inventory Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 60px; /* Adjusted padding for the fixed navbar */
            text-align: center;
        }

        .navbar-brand,
        .nav-link {
            color: black;
            font-weight: bold;
        }

        .container {
            margin-top: 20px;
        }

        h2 {
            color: #343a40;
        }

        table {
            border-radius: 20px;
        }

        th,
        td {
            text-align: center;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg" style="padding-top: -40px;">
        <a class="navbar-brand" href="user_dashboard.php">
            <img src="somaport-removebg-preview.png" alt="Logo" style="width: 60%;" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav" style="font-size: 1rem;">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="user_add_product.php">Ajouter un produit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user_settings.php">Paramètres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h2 class="text-center" style="font-size: 3rem;">Liste des Produits</h2>
        <!-- Ajouter l'entrée de recherche -->
        <form method="GET" action="">
            <div class="form-group">
                <input type="text" class="form-control" name="search" placeholder="Rechercher par nom de produit"
                    value="<?php echo htmlspecialchars($searchTerm); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
        <br>
        <!-- Modifier la table pour avoir un ID pour un ciblage plus facile en JavaScript -->
        <div class="table-responsive">
            <table id="productTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID Produit</th>
                        <th>Nom du Produit</th>
                        <th>Description</th>
                        <th>Quantité</th>
                        <th>Code QR</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['name']}</td>";
                        echo "<td>{$row['description']}</td>";
                        echo "<td>{$row['quantity']}</td>";
                        echo "<td><img src='{$row['qrcode']}' alt='Code QR' style='width: 30%;'></td>";
                        // Lien de consultation
                        echo "<td><a href='user_consult.php?product_id={$row['id']}' class='btn btn-info'>Consulter</a></td>";
                        // Bouton de mise à jour en utilisant un formulaire
                        echo "<td>";
                        echo "<form method='POST' action='user_update.php'>";
                        echo "<input type='hidden' name='product_id' value='{$row['id']}'>";
                        echo "<button type='submit' class='btn btn-primary'>Mettre à Jour</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Pagination -->
    <nav aria-label="Pagination des produits">
        <ul class="pagination justify-content-center mt-4">
            <?php
            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'><a class='page-link' href='?search=$searchTerm&page=$i'>$i</a></li>";
            }
            ?>
        </ul>
    </nav>
    <a href="user_dashboard.php" style="font-size: 1.4rem;">Retour</a>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
