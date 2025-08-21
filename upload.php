<?php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['scanned_form']) && $_FILES['scanned_form']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['scanned_form']['tmp_name'];
        $fileName = $_FILES['scanned_form']['name'];
        $fileSize = $_FILES['scanned_form']['size'];
        $fileType = $_FILES['scanned_form']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Allowed file extensions
        $allowedExtensions = array('pdf');

        // Validate file extension and MIME type
        if (in_array($fileExtension, $allowedExtensions) && $fileType === 'application/pdf') {
            $uploadFileDir = './uploaded_files/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $sql = "INSERT INTO approved_forms (filename, filepath, timestamp) VALUES (?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $fileName, $dest_path);

                if ($stmt->execute()) {
                    echo "File is successfully uploaded.";
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Invalid file type. Only PDF files are allowed.";
        }
    } else {
        echo "No file uploaded or there was an upload error.";
    }
}

$conn->close();
?>
