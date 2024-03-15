
<?php
// admin_update_user.php

include('config.php');

// Check if a user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_users.php");
    exit();
}

// Function to update a user
function updateUser($userId, $newUsername, $newPassword) {
    global $conn;
    $newUsername = mysqli_real_escape_string($conn, $newUsername);
    $newPassword = mysqli_real_escape_string($conn, $newPassword);

    $sql = "UPDATE users SET username='$newUsername', password='$newPassword' WHERE id = $userId";
    $result = $conn->query($sql);
    return $result;
}

// Fetch the user's current information
$userId = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = $userId";
$result = $conn->query($sql);

// Check if the user exists
if ($result->num_rows == 0) {
    header("Location: view_users.php");
    exit();
}

$userData = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['new_username'];
    $newPassword = $_POST['new_password'];

    if (updateUser($userId, $newUsername, $newPassword)) {
        header("Location: view_users.php");
        exit();
    } else {
        echo "<script>alert('Error updating user');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User - Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
   
    <!-- Navbar (You can reuse the navbar from the dashboard) -->

    <div class="container mt-4">
        <h2 class="text-center">Mise à jour de l'utilisateur</h2>

        <form action="" method="post">
            <div class="form-group">
                <label for="new_username">Nouveau nom d'utilisateur :</label>
                <input type="text" class="form-control" id="new_username" name="new_username" value="<?php echo $userData['username']; ?>" required>
            </div>

            <div class="form-group">
                <label for="new_password">Nouveau mot de passe :</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour l'utilisateur</button>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>


</html>
