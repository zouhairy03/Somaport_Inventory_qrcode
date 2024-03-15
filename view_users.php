<?php
// view_users.php

include('config.php');

function deleteUser($userId) {
    global $conn;
    $sql = "DELETE FROM users WHERE id = $userId";
    $result = $conn->query($sql);
    return $result;
}

$searchName = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM users";

if (!empty($searchName)) {
    $sql .= " WHERE username LIKE '%$searchName%'";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir les utilisateurs - Tableau de bord administrateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            text-align: center;
        }

        .navbar-brand,
        .nav-link {
            color: #343a40;
            font-weight: bold;
        }

        .navbar-toggler-icon {
            background-color: #343a40;
        }

        .container {
            margin-top: 20px;
        }

        h2 {
            color: #424242;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            height: auto;
            width: 60%;
        }

        .btn-update,
        .btn-delete {
            width: 80px;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->


    <!-- Contenu principal -->
    <div class="container mt-4">
        <!-- Formulaire de recherche -->
        <form action="view_users.php" method="get" class="form-inline justify-content-center mb-4">
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" class="form-control" name="search" placeholder="Rechercher par nom" value="<?php echo $searchName; ?>">
            </div>
            <button type="submit" class="btn btn-primary mb-2">Rechercher</button>
        </form>

        <h2 class="text-center">Voir les utilisateurs</h2>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Mot de passe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["username"] . "</td>";
                            echo "<td>" . $row["password"] . "</td>";
                            echo "<td>";
                            echo "<a href='admin_update_user.php?id=" . $row["id"] . "' class='btn btn-warning btn-update'>Update </a> ";
                            echo "<a href='view_users.php?delete=" . $row["id"] . "' class='btn btn-danger btn-delete' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet utilisateur ?\")'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Aucun utilisateur trouvé</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    if (isset($_GET['delete'])) {
        $userIdToDelete = $_GET['delete'];
        if (deleteUser($userIdToDelete)) {
            header("Location: view_users.php");
            exit();
        } else {
            echo "<script>alert('Erreur lors de la suppression de l'utilisateur');</script>";
        }
    }
    ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
