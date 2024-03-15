<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

// Include database connection details
include_once "config.php"; // Replace with your actual database connection file

// Fetch products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

// Check if there are products in the database
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List - Inventory Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

       

        .navbar-brand,
        .nav-link {
            color: black;
            font-weight: bold;
        }

        .container {
            margin-top: 20px;
        }

        h2 {
            color: #343a40;
        }

        table {
            background-color: rgba(121, 172, 49,0.493);
            /*box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
            border-radius: 20px;
            /*border-color:green;*/
        }

        th,
        td {
            text-align: center;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg ">
        <a class="navbar-brand" href="#">
            <img src="somaport-removebg-preview.png" alt="Logo" height="30" style="margin-left:40px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="settings.php" style="color:black;">Paramètres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" >Déconnexion</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4" >
        <h2 class="text-center" style="font-size:3rem;">Liste des Produits</h2>

        <!-- Display the list of products -->
        <div class="table-responsive" >
            <table class="table table-hover"  >
                <thead  style="border-raduis:20px;">
                    <tr>
                        <th>ID du Produit</th>
                        <th>Nom du Produit</th>
                        <th>Description</th>
                        <th>Quantité</th>
                        <th>Code QR</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['name']}</td>";
                        echo "<td>{$row['description']}</td>";
                        echo "<td>{$row['quantity']}</td>";
                        echo "<td><img src='{$row['qrcode']}' alt='QR Code'></td>";

                        // Add a link to consult.php with product ID as a parameter
                        echo "<td><a href='https://hamzalahrach.online/consult.php?product_id={$row['id']}'>Consulter</a></td>";

                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <a href="dashboard.php" style="margin-left:600px;font-size:1.4rem;">Retour</a>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
