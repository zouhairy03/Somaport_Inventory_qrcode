<?php
// view_products.php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include('config.php');
function searchProductsByName($name) {
    global $conn;
    $name = mysqli_real_escape_string($conn, $name);
    $sql = "SELECT * FROM products WHERE name LIKE '%$name%'";
    $result = $conn->query($sql);
    return $result;
}
function exportToExcel($data) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Add header row
    $sheet->fromArray(['ID', 'Name', 'Description', 'Quantity', 'QR Code'], NULL, 'A1');
    // Add data rows
    $row = 2;
    foreach ($data as $rowdata) {
        $sheet->fromArray($rowdata, NULL, 'A' . $row);
        $row++;
    }
    // Set headers for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="products.xlsx"');
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
// Handle search form submission and Excel export
if (isset($_POST['search'])) {
    $searchName = $_POST['searchName'];
    $result = searchProductsByName($searchName);
} elseif (isset($_POST['export'])) {
    // Export all products to Excel
    $result = $conn->query("SELECT * FROM products");
    exportToExcel($result->fetch_all(MYSQLI_ASSOC));
} else {
    // Default query to fetch all products
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products - Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add any additional styles you need -->
</head>
<body class="bg-light">
    <!-- Navigation bar with branding on the left side and logout button on the right side-->
    <div class="container mt-4">
        <h2 class="text-center">Voir les produits</h2>
        <div>
            <!-- Form for search and export -->
            <form action="view_products.php" method="post" class="mb-3">
                <div class="form-row">
                    <div class="col-7">
                        <input type="text" class="form-control" name="searchName" placeholder="Rechercher par nom">
                    </div>

                    <div class="col" style="display: flex; justify-content:space-between; gap:9px;">
                        <button type="submit" class="btn btn-primary" name="search">Rechercher</button>
                        <button type="submit" class="btn btn-success" name="export">Exporter vers Excel</button>
                        <a href="add_product.php" class="btn btn-info">Ajouter un produit</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Quantité</th>
                        <th>Code QR</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["description"] . "</td>";
                            echo "<td>" . $row["quantity"] . "</td>";
                            echo "<td><img src='" . $row["qrcode"] . "' alt='Code QR' style='width: 50px; height: 50px;'></td>";
                            echo "<td>";
                            echo "<a href='admin_delete.php?id=" . $row["id"] . "' class='btn btn-danger btn-delete' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce produit ?\")'>Supprimer</a>";
                            echo "<a href='update_product.php?id=" . $row["id"] . "' class='btn btn-warning btn-update ml-2'>Mettre à jour</a>"; // Ajout de la marge
                            echo "<a href='consult_product.php?id=" . $row["id"] . "' class='btn btn-secondary btn-consult ml-2'>Consulter</a>"; // Bouton de consultation
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Aucun produit trouvé</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>


</html>
