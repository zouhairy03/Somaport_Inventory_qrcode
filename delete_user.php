<?php
// delete_user.php

// Include configuration
require_once 'config.php';

// Start the session
session_start();

// Function to check if an admin or the user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']) || isset($_SESSION['user_id']);
}

// Redirect to the login page if not logged in
if (!isLoggedIn()) {
    header("Location: index.php");
    exit();
}

// Function to delete a user from the database
function deleteUser($userId, $conn) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);

    return $stmt->execute();
}

// Get the user ID to delete
$userIdToDelete = null;

// Check if the logged-in user is an admin
if (isset($_SESSION['admin_id'])) {
    // Admin can delete any user by providing user ID in the URL
    if (isset($_GET['id'])) {
        $userIdToDelete = $_GET['id'];
    }
} elseif (isset($_SESSION['user_id'])) {
    // Users can only delete their own account
    $userIdToDelete = $_SESSION['user_id'];
}

// Display confirmation message
$confirmationMessage = "Are you sure you want to delete this account?";
$confirmationMessage .= "<br>";
$confirmationMessage .= "<a href='delete_user.php?id={$userIdToDelete}&confirm=yes' class='btn btn-danger'>Yes, delete account</a>";
$confirmationMessage .= "&nbsp;";
$confirmationMessage .= "<a href='user_dashboard.php' class='btn btn-secondary'>No, go back</a>";

// Check if confirmation is clicked
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Delete the user
    if (deleteUser($userIdToDelete, $conn)) {
        $confirmationMessage .= "<br>";
        $confirmationMessage .= "<div class='alert alert-success'>Account deleted successfully!</div>";
        // Redirect to logout for the user to log out after deleting their account
        header("Location: index.php");
        exit();
    } else {
        $confirmationMessage .= "<br>";
        $confirmationMessage .= "<div class='alert alert-danger'>Error deleting account.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-header">Supprimer le compte utilisateur</div>
            <div class="card-body">
                <?php echo $confirmationMessage; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
