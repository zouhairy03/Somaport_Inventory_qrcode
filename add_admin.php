<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param('ss', $username, $password);

    if ($stmt->execute()) {
        echo "Admin added successfully!";
    } else {
        echo "Error adding admin: " . $stmt->error;
    }

    $stmt->close();
}

$result = $conn->query("SELECT id, username, password FROM admins");
$admins = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un administrateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <style>
        body{
            text-align: center;
        }
    </style>
    <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="somaport-removebg-preview.png" alt="Logo" class="img-fluid">
        </a>
    </nav> -->

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Ajouter un administrateur</h2>
                <form action="add_admin.php" method="post">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur :</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Ajouter l'administrateur</button>
                </form>
            </div>
        </div>

        <!-- Afficher la liste des administrateurs -->
        <h2 class="mt-4 text-center">Liste des administrateurs</h2>
        <table class="table">
    <thead class="">
        <tr>
            <th scope="col">ID de l'admin</th>
            <th scope="col">Nom d'utilisateur</th>
            <th scope="col">Mot de passe</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($admins as $admin): ?>
            <tr>
                <td><?php echo $admin['id']; ?></td>
                <td><?php echo $admin['username']; ?></td>
                <td><?php echo $admin['password']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
