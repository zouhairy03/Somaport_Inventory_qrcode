<?php
// user_dashboard.php

// Start the session
session_start();

// Function to check if a user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['user_id']) && !isset($_SESSION['admin_id']);
}

// Redirect to the login page if not logged in as a user
if (!isUserLoggedIn()) {
    header("Location: index.php");
    exit();
}

// Function to get the welcome message for the user
function getWelcomeMessage() {
    if (isset($_SESSION['user_id'], $_SESSION['username'])) {
        return "Bienvenue , {$_SESSION['username']}!";
    } else {
        return "Bienvenue";
    }
}

// Get the welcome message
$welcomeMessage = getWelcomeMessage();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Inventory Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #f1f1f1; /* Light Gray */
            font-family: 'Roboto', sans-serif; /* Change font family */
        }

        .navbar {
            color: #333; /* Dark Gray */
        }

        .navbar-brand,
        .nav-link {
            color: #79ac31; /* Logo Green */
            font-weight: bold;
          /* Adjust font size */
        }



        .container {
            margin-top: 20px;
        }

      

        .dashboard-section {
            background-color: #fff;
            border: 1px solid #ddd; /* Light Gray */
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        button {
            background-color: #4caf50; /* Logo Green */
            border-color: #4caf50;
            color: #fff;
            font-size: 1rem; /* Adjust font size */
        }

        button:hover {
            background-color: #3e8e41; /* Darker Logo Green */
            border-color: #3e8e41;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            height: auto;
            width: 60%;
            max-width: 100%; /* Make the logo responsive */
        }

        .content {
            background-color: #1a1718; /* Dark Gray */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
        }

        .section-title {
            color: #333; /* Dark Gray */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="user_dashboard.php">
            <img src="somaport-removebg-preview.png" alt="Logo" style="width: 60%;" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="user_settings.php"  style="font-size: rem;">Paramètres <i class="fa-solid fa-gear"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"  style="font-size: rem;">Déconnexion <i class="fa-solid fa-right-from-bracket"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center"><?= $welcomeMessage ?> Utilisateur</h2>
        <br><br>

        <div class="row">
            <?php foreach (['Ajouter un Nouveau Produit' => 'user_add_product.php', 'Voir la Liste des Produits' => 'user_list_product.php'] as $title => $action): ?>
                <div class="col-md-6">
                    <div class="dashboard-section">
                        <h3 class="text-center section-title"><?= $title ?></h3>
                        <form action="<?= $action ?>" method="post">
                            <input type="hidden" name="<?= strtolower(str_replace(' ', '_', $title)) ?>_trigger" value="1">
                            <button type="submit" class="btn btn-dark btn-block " style="background: #3e8e41;"><?= $title ?></button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
