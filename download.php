<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: forms.html');
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formsdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize input to prevent SQL injection
$file = $conn->real_escape_string($_GET['file']);

// Query the database to get the file path
$sql = "SELECT filepath FROM pdfs WHERE filename='$file'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $filepath = $row['filepath'];

    // Check if the file exists
    if (file_exists($filepath)) {
        // Set headers to initiate file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));

        // Output the file
        readfile($filepath);
        exit();
    } else {
        echo "File not found.";
    }
} else {
    echo "Invalid file.";
}

// Close the database connection
$conn->close();
?>
