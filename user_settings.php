<!-- user_settings.php -->
<?php
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
    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        return "Welcome, " . $_SESSION['username'] . "!"; // Display the username for a regular user
    } else {
        return "Welcome!";
    }
}

// Get the welcome message
$welcomeMessage = getWelcomeMessage();

// Assuming you have a database connection, replace this with your actual connection code
$conn = new mysqli("localhost", "root", "root", "qrcode");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details from the database based on user_id
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch user details as an associative array
$userDetails = $result->fetch_assoc();

?>
<!-- user_settings.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings - Inventory Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your custom styles if needed -->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="somaport-removebg-preview.png" alt="Logo" style="width: 50%;" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="user_settings.php">Paramètres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center"><?php echo $welcomeMessage; ?></h2>

        <div class="content" style="text-align: center;">
            <h3 class="text-center">Paramètres de l'utilisateur</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Champ</th>
                        <th scope="col">Valeur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($userDetails as $field => $value) {
                        echo "<tr><td>$field</td><td>$value</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Form for updating user information -->
            <form action="update_user.php" method="post">
                <!-- Ajoutez des champs de formulaire en fonction de votre schéma de base de données -->
                <button type="submit" class="btn btn-primary" >Mettre à jour les informations</button>
            </form>
<br>
            <!-- Form for deleting user account -->
            <!-- <form action="delete_user.php" method="post">
                <button type="submit" class="btn btn-danger" >Supprimer le compte</button>
            </form> -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
