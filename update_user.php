<?php
// update_user.php

// Include configuration and database connection
require_once 'config.php';

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

// Get user details from the database
$userDetails = getUserDetails($_SESSION['user_id'], $conn);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'new_username' and 'new_password' are set in the form submission
    if (isset($_POST['new_username']) && isset($_POST['new_password'])) {
        $newUsername = $_POST['new_username'];
        $newPassword = $_POST['new_password'];

        // Check if the new username is different from the current one
        if ($newUsername !== $userDetails['username']) {
            // Check if the new username is already in use
            if (!isUsernameTaken($newUsername, $conn)) {
                // Update user information in the database
                if (updateUser($_SESSION['user_id'], $newUsername, $newPassword, $conn)) {
                    // Redirect back to user_settings.php after updating
                    header("Location: user_settings.php");
                    exit();
                } else {
                    $error_message = "Error updating user information";
                }
            } else {
                $error_message = "The new username is already in use. Please choose a different one.";
            }
        } else {
            $error_message = "The new username is the same as the current one.";
        }
    } else {
        $error_message = "New username or password is not set";
    }
}

// Function to check if a username is already taken
function isUsernameTaken($username, $conn) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    return ($result->num_rows > 0);
}

// Function to get user details based on user ID
function getUserDetails($userId, $conn) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return false;
}

// Function to update user information in the database
function updateUser($userId, $newUsername, $newPassword, $conn) {
    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssi", $newUsername, $newPassword, $userId);

    return $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à jour le compte utilisateur - Gestion des stocks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 20px;
        }

        h2 {
            color: #424242;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Mettre à jour les informations de l'utilisateur</h2>

        <!-- Afficher un message d'erreur s'il y en a un -->
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de mise à jour de l'utilisateur -->
        <form action="update_user.php" method="post">
            <div class="form-group">
                <label for="new_username">Nouveau nom d'utilisateur :</label>
                <input type="text" class="form-control" id="new_username" name="new_username" required>
            </div>
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe :</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour l'utilisateur</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
