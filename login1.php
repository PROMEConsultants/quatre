<?php
session_start(); // Start the session

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data and sanitize it
    $Email = trim($_POST['Email'] ?? '');
    $Password = trim($_POST['Password'] ?? '');

    // Debugging: Echo the sanitized inputs to verify their values
    echo "Email: $Email<br>";
    echo "Password: $Password<br>";

    // Check for empty input
    if (empty($Email) || empty($Password)) {
        echo "<p class='error'>Please fill in both fields.</p>";
        exit();
    }

    // Database connection
    $servername = "localhost";
    $username = "root";
    $dbpassword = ""; // Database password
    $dbname = "signin"; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use a prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM signin WHERE Email = ? AND presetpassword = ?");
    $stmt->bind_param("ss", $Email, $Password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the user data
    if ($row = $result->fetch_assoc()) {
        // Debugging: Echo retrieved row data
        echo "Retrieved User: " . print_r($row, true);

        // Check the user's role and redirect accordingly
        if ($row['Email'] == $Email && $row['presetpassword'] == $Password) {
            $_SESSION['logged_in'] = true;

            if ($row['role'] == 'admin') {
                header('Location: protectedadmin.php'); // Redirect to admin page
                exit();
            } elseif ($row['role'] == 'staff') {
                header('Location: protected.php'); // Redirect to staff page
                exit();
            }
        }
    } else {
        echo "<p class='error'>Invalid Email or password.</p>";
        header('Location: forms.html'); // Redirect back to the login form on failure
        exit();
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
