<?php
session_start();
require 'database_connection.php'; 

$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "formsdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Replace with actual DB connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $signature = $_POST['signature']; // Get signature data
    $formId = $_POST['form_id']; // Get the form ID

    // Save the signature in the database
    $sql = "UPDATE forms SET signature='$signature', status='signed' WHERE id=$formId";
    if (mysqli_query($conn, $sql)) {
        echo "Signature saved successfully!";
    } else {
        echo "Error saving signature: " . mysqli_error($conn);
    }
}
?>
