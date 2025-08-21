<?php
// Database connection details
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "formsdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch PDF metadata from the database
$sql = "SELECT id, filename, filepath FROM pdfs";
$result = $conn->query($sql);

echo "<h1>Available PDF Forms</h1>";

// Display each PDF as a link
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . $row["filename"] . "</h3>";
        echo "<a href='download.php?file=" . urlencode($row["filename"]) . "'>Download " . $row["filename"] . "</a>";
        echo "</div><hr>";
    }
} else {
    echo "No PDFs available.";
}

$conn->close();
?>
