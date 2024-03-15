<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

// Dummy admin data (replace this with your actual data retrieval logic)
$adminData = [
    'admin_id' => 1,  // Change this to the actual admin_id
    'username' => 'admin',
    // Add more admin details as needed
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres administrateur - Gestion des stocks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            margin-bottom: 10px;
        }

        .navbar-brand,
        .nav-link {
            color: #343a40;
            font-weight: bold;
        }

        .container {
            margin-top: 20px;
        }

        h2 {
            color: #343a40;
        }

        form {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        label {
            color: #343a40;
        }

        button {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
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
                    <a class="nav-link" href="dashboard.php" style="color:black;">Tableau de bord</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" style="color:black;">Déconnexion</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">Paramètres administrateur</h2>
        <form action="update_account.php" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" class="form-control" value="<?php echo $adminData['username']; ?>">
            </div>
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe :</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <!-- Add more fields as needed -->

            <button type="submit" class="btn btn-primary btn-block"
                style="background: RGB(121, 172, 49); border-color: RGB(121, 172, 49);">Mettre à jour le compte</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
