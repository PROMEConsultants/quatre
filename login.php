<?php
session_start();

// Create a connection to the database
$conn = new mysqli('localhost', 'root', '', 'signin');

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data and sanitize it
    $FirstName = $_POST['FirstName'] ?? '';
    $Password = $_POST['Password'] ?? '';

    // Validate form data
    if (empty($FirstName) || empty($Password)) {
        echo json_encode(['message' => 'Please fill in all required fields.']);
        exit();
    }

    // Prepare and execute SQL query to fetch user data
    $stmt = $conn->prepare("SELECT presetpassword FROM signin WHERE firstname = ?");
    $stmt->bind_param("s", $FirstName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(['message' => 'Invalid username or password.']);
        exit();
    }

    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
// Add this before the password verification
error_log("Fetched hashed password: " . $hashedPassword);
error_log("Provided password: " . $Password);

    // Verify the provided password against the hashed password

    if (password_verify($Password, $hashedPassword)) {
        $_SESSION['logged_in'] = true;
        header('Location: protected.php'); // Redirect to a protected page
        exit();
    } else {
        echo json_encode(['message' => 'Invalid username or password.']);
    }

    $stmt->close();
    $conn->close();

} else {
    echo json_encode(['message' => 'Invalid request method!']);
}
?>
