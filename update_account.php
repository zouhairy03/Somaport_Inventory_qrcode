<?php
session_start();

// Include your database connection file (config.php)
require_once 'config.php';

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the new password from the form and hash it
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Get the admin ID from the session or database (replace with your logic)
    $adminId = 1; // Change this to the actual admin_id or fetch it from your session/database

    // Update the password in the admin_table
    $updateQuery = "UPDATE admin_table SET password = '$newPassword' WHERE admin_id = $adminId";

    if ($conn->query($updateQuery) === TRUE) {
        // Redirect back to the settings page or dashboard after updating
        header("Location: settings.php");
        exit();
    } else {
        // Output the SQL error for debugging
        echo "Error updating record: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
