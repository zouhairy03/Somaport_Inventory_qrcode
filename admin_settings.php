<?php
// admin_settings.php

// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Include your database connection or config file
include('config.php');

// Fetch admin details from the database
$adminId = $_SESSION['admin_id'];
$sql = "SELECT * FROM admins WHERE id = $adminId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $adminData = $result->fetch_assoc();
} else {
    // Handle the case where admin data is not found
    echo "Error: Admin data not found.";
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form data
    $newUsername = $_POST['adminUsername'];
    $newPassword = $_POST['adminPassword'];

    // Update the admin's settings in the database
    $updateSql = "UPDATE admins SET username = '$newUsername', password = '$newPassword' WHERE id = $adminId";

    if ($conn->query($updateSql) === TRUE) {
        echo "Admin settings updated successfully.";
    } else {
        echo "Error updating admin settings: " . $conn->error;
    }

    // Fetch the updated admin details
    $sql = "SELECT * FROM admins WHERE id = $adminId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $adminData = $result->fetch_assoc();
    } else {
        // Handle the case where admin data is not found
        echo "Error: Admin data not found after update.";
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add any additional stylesheets if needed -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }

        h2 {
            color: #343a40;
        }

        .password-toggle {
            cursor: pointer;
            color: #007bff;
        }
    </style>
</head>
<body>
    <!-- Navbar (You can reuse the navbar from the dashboard) -->

    <div class="container mt-4">
        <h2 class="text-center">Paramètres de l'administrateur</h2>

        <!-- Your form for updating admin settings -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Add your form fields for updating settings -->

            <div class="form-group">
                <label for="adminUsername">Nom d'utilisateur :</label>
                <input type="text" class="form-control" id="adminUsername" name="adminUsername" value="<?php echo $adminData['username']; ?>" required>
            </div>

            <div class="form-group">
                <label for="adminPassword">Mot de passe :</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="adminPassword" name="adminPassword" value="<?php echo $adminData['password']; ?>" required>
                    <div class="input-group-append">
                        <span class="input-group-text password-toggle" onclick="togglePassword()">Afficher</span>
                    </div>
                </div>
            </div>

            <!-- Add more form fields as needed -->

            <button type="submit" class="btn btn-primary">Mettre à jour les paramètres</button>
        </form>
    </div>

    <!-- Include your script tags and other scripts if needed -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("adminPassword");

            // Toggle the password visibility
            passwordInput.type = (passwordInput.type === "password") ? "text" : "password";

            // Trigger focus to bring cursor to the end of the password input
            passwordInput.focus();
        }
    </script>
</body>


</html>
