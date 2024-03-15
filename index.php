<?php
// index.php

// Inclure la configuration
require_once 'config.php';

// Démarrer la session
session_start();

// Fonction pour vérifier si un utilisateur est connecté
function isUserLoggedIn() {
    return isset($_SESSION['user_id']) && !isset($_SESSION['admin_id']);
}

// Fonction pour vérifier si un administrateur est connecté
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Fonction pour rediriger vers le tableau de bord de l'utilisateur
function redirectToUserDashboard() {
    header("Location: user_dashboard.php");
    exit();
}

// Fonction pour rediriger vers le tableau de bord de l'administrateur
function redirectToAdminDashboard() {
    header("Location: admin_dashboard.php");
    exit();
}

// Fonction pour authentifier l'utilisateur
function authenticateUser($username, $password, $conn) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        return true;
    }

    return false;
}

// Fonction pour authentifier l'administrateur
function authenticateAdmin($username, $password, $conn) {
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['admin_id'] = $row['id'];
        return true;
    }

    return false;
}

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenir les entrées utilisateur
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Authentifier l'utilisateur
    if (authenticateUser($username, $password, $conn)) {
        // Rediriger vers le tableau de bord de l'utilisateur
        redirectToUserDashboard();
    }

    // Authentifier l'administrateur
    if (authenticateAdmin($username, $password, $conn)) {
        // Rediriger vers le tableau de bord de l'administrateur
        redirectToAdminDashboard();
    }

    // Si ni l'authentification de l'utilisateur ni celle de l'administrateur n'est réussie
    $error_message = "Nom d'utilisateur ou mot de passe invalide";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>
    <!-- Lien CSS Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; /* Centrer le contenu */
        }

        .login-logo {
            margin-bottom: 20px;
        }

        .login-header {
            margin-bottom: 20px;
        }

        .login-form {
            margin-bottom: 20px;
        }

        .register-link {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="col-md-6 offset-md-3 login-container">
        <!-- Ajouter votre logo ici -->
        <div class="login-logo">
            <img src="somaport-removebg-preview.png" style="width:30%;" alt="Votre logo">
        </div>

        <h2 class="login-header">Gestion inventaire </h2>

        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="" class="login-form">
            <div class="form-group">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-success btn-block">Connexion</button>
        </form>

        <p class="mt-3 register-link">Si vous n'avez pas de compte, <a href="user_register.php">inscrivez-vous ici</a>.</p>
    </div>
</div>

<!-- Scripts Bootstrap JS et Popper.js -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>
