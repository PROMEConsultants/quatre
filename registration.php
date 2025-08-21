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
    $LastName = $_POST['LastName'] ?? '';
    $Password = $_POST['Password'] ?? '';

    // Validate form data
    if (empty($FirstName) || empty($LastName) || empty($Password)) {
        echo json_encode(['message' => 'Please fill in all required fields.']);
        exit();
    }

    // Hash the password before storing it
    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

    // Prepare and bind the SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO signin (firstname, lastname, presetpassword, changedpassword,role) VALUES (?, ?, ?, 0, ?)");

    if (!$stmt) {
        echo json_encode(['message' => 'Error preparing statement: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("sss", $FirstName, $LastName, $hashedPassword);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['message' => 'User registered successfully']);
    } else {
        echo json_encode(['message' => 'Error executing statement: ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

} else {
    echo json_encode(['message' => 'Invalid request method!']);
}
?>
