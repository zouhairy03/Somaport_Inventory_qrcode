<?php
// user_registration.php

// Include configuration
require_once 'config.php';

// Start session
session_start();

// Function to redirect to the dashboard or any specified page
function redirectToPage($page) {
    header("Location: $page");
    exit();
}

// Function to register a new user
function registerUser($username, $password, $conn) {
    // Check if the username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return false; // Username already exists
    }

    // Insert new user into the database without hashing the password
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        return true; // Registration successful
    } else {
        echo "Error: " . $stmt->error;
        return false; // Registration failed
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Register the new user
    if (registerUser($username, $password, $conn)) {
        // Set success message
        $_SESSION['success_message'] = "Registration successful. You can now log in.";

        // Redirect to index.php after successful registration
        // redirectToPage('index.php');
    } else {
        // Set error message
        $_SESSION['error_message'] = "Registration failed. Please choose a different username.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire</title>
    <!-- Lien CSS Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="col-md-6 offset-md-3">
        <h2 class="text-center mb-4">S'inscrire</h2>

        <?php if (isset($_SESSION['success_message'])) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php elseif (isset($_SESSION['error_message'])) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
        </form>

        <p class="mt-3 text-center">Vous avez déjà un compte? <a href="index.php">Connectez-vous ici</a>.</p>
    </div>
</div>

<!-- Scripts Bootstrap JS et Popper.js -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>
