<?php
include_once 'config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the user is an admin
    if ($username === "admin" && $password === "admin") {
        $_SESSION["username"] = $username;
        $_SESSION["is_admin"] = true;
        header("Location:dashboard.php");
        exit();
    } else {
        // Check against the user table for non-admin users
        // Add your database query here to check the user credentials
        // If the user exists and the password is correct, set session variables and redirect
        // Otherwise, display an error message
    }
}
?>
<!-- The rest of your HTML code for the login page -->
