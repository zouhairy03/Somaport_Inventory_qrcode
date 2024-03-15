<?php
// add_user.php

include('config.php');

// Function to add a new user
function addUser($username, $password) {
    global $conn;
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    $result = $conn->query($sql);
    return $result;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['new_username'];
    $newPassword = $_POST['new_password'];

    if (addUser($newUsername, $newPassword)) {
        header("Location: view_users.php");
        exit();
    } else {
        echo "<script>alert('Error adding user');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur - Tableau de bord administrateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Navbar (Vous pouvez réutiliser la barre de navigation du tableau de bord) -->

    <div class="container mt-4">
        <h2 class="text-center">Ajouter un utilisateur</h2>

        <form action="" method="post">
            <div class="form-group">
                <label for="new_username">Nom d'utilisateur :</label>
                <input type="text" class="form-control" id="new_username" name="new_username" required>
            </div>

            <div class="form-group">
                <label for="new_password">Mot de passe :</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <button type="submit" class="btn btn-primary">Ajouter l'utilisateur</button>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
