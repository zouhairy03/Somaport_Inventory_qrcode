<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']);
}

if (!isLoggedIn()) {
    header("Location: index.php");
    exit();
}

function getWelcomeMessage() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        return "Bienvenue, " . $_SESSION['username'] . "!";
    } elseif (isset($_SESSION['admin_id'])) {
        return "Bienvenue, Admin!";
    } else {
        return "Bienvenue!";
    }
}

$welcomeMessage = getWelcomeMessage();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord administrateur - Gestion des stocks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Ajoutez vos styles CSS personnalisés ici -->
    <style>
        /* Vos styles personnalisés */
    </style>
</head>
<body>
    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="somaport-removebg-preview.png" alt="Logo" style="width: 60%;" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin_settings.php">Paramètres <i class="fa-solid fa-gear"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_admin.php">Ajouter un administrateur <i class="fa-sharp fa-light fa-plus"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion <i class="fa-solid fa-right-from-bracket"></i></a>
                </li>
            </ul>
        </div>
    </nav>


    <!-- Contenu principal -->
    <div class="container mt-4">
        <h2 class="text-center"><?php echo $welcomeMessage; ?></h2>

        <div class="row">
            <!-- Section Utilisateurs -->
            <div class="col-md-6">
                <div class="dashboard-section">
                    <h3 class="text-center section-title"><img src="team-management.png" style="width: 20%;" alt=""></h3>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="view_users.php" class="btn btn-dark btn-block">Voir les utilisateurs</a>
                        </li>
                        <li class="list-group-item">
                            <a href="add_user.php" class="btn btn-dark btn-block" style="background:green;">Ajouter un utilisateur</a>
                        </li>
                        <!-- Ajoutez d'autres actions liées aux utilisateurs au besoin -->
                    </ul>
                </div>
            </div>

            <!-- Section Produits -->
            <div class="col-md-6">
                <div class="dashboard-section">
                    <h3 class="text-center section-title"><img src="product-development (1).png" style="width: 20%;" alt=""></h3>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="view_products.php" class="btn btn-dark btn-block">Voir les produits</a>
                        </li>
                        <li class="list-group-item">
                            <a href="add_product.php" class="btn btn-dark btn-block" style="background:green;">Ajouter un produit</a>
                        </li>
                        <!-- Ajoutez d'autres actions liées aux produits au besoin -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
