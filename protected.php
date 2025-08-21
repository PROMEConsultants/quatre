<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: forms.html');
    exit();
}

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
$available_sql = "SELECT id, filename, filepath FROM pdfs";
$available_result = $conn->query($available_sql);

if (!$available_result) {
    die("Query failed: " . $conn->error);
}

// Fetch approved forms metadata from the database
$approved_sql = "SELECT filename, filepath, timestamp FROM approved_forms WHERE status='approved' ORDER BY timestamp DESC";
$approved_result = $conn->query($approved_sql);

if (!$approved_result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prome Consultants Pdfs</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .container {
            width: 80%;
            margin: auto;
        }
        .form-section {
            margin-bottom: 20px;
        }
        h1, h2 {
            color: #333;
        }
        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .box {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Prome Consultants Forms</h1>

        <div class="content">
            <!-- Available Forms Section -->
            <div class="box">
                <h2>Available Forms</h2>
                <?php
                if ($available_result->num_rows > 0) {
                    while ($row = $available_result->fetch_assoc()) {
                        echo "<div>";
                        echo "<h3>" . htmlspecialchars($row["filename"]) . "</h3>";
                        echo "<a href='download.php?file=" . urlencode($row["filename"]) . "'>Download " . htmlspecialchars($row["filename"]) . "</a>";
                        echo "</div><hr>";
                    }
                } else {
                    echo "No PDFs available.";
                }
                ?>
                
   
   
            </div>
            <div class="box">
                
            <!-- Approved Forms Section -->
            <div class="box">
                <h2>Approved Forms</h2>
                <?php if ($approved_result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Download Link</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $approved_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row["filename"]); ?></td>
                                    <td><a href="<?php echo htmlspecialchars($row["filepath"]); ?>" target="_blank">View</a></td>
                                    <td><?php echo htmlspecialchars($row["timestamp"]); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No approved forms available.</p>
                <?php endif; ?>
            </div>
        </div>
    
        <div class="image-capture-section">

    <h2>Upload Scanned Form</h2>
   
    <form  method="post" enctype="multipart/form-data">
    <label for="fileToUpload">Capture or Select a File:</label>
    <input type="file" name="fileToUpload" id="doc" accept="image/*;capture=camera">
    <input type="submit" value="Upload Scanned Form" name="submit">

    </form>

</div>
<?php
if (isset($_POST['submit'])) {  // Match 'submit' exactly
    // File Handling
    $file = $_FILES['fileToUpload'];
    $games_image = $file['name'];
    $games_tmp = $file['tmp_name'];

    // Error handling: Check if the file was uploaded
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code " . $file['error']);
    }

    // Get file extension and create a new file name
    $extension = pathinfo($games_image, PATHINFO_EXTENSION);
    $fileName = pathinfo($games_image, PATHINFO_FILENAME) . '.' . $extension;

    // Move the uploaded file to the 'documents' directory
    if (move_uploaded_file($games_tmp, "documents/$fileName")) {
        echo "File uploaded successfully!";
    } else {
        die("Failed to move uploaded file.");
    }

    // SQL Insertion
    $timestamp = date('Y-m-d H:i:s'); // Add current timestamp
    $filepath = "documents/$fileName"; // Define the filepath
    $status = 'approved'; // Default status
    
    $sql = "INSERT INTO `approved_forms` (`id`, `filename`, `filepath`, `timestamp`, `status`) VALUES (NULL, '$fileName', '$filepath', '$timestamp', '$status')";

    // Error handling: Check if the query is successful
    if (mysqli_query($conn, $sql)) {
        echo "Record inserted successfully!";
        ?>
        <script type="text/javascript" language="javascript">
            window.location = "protected.php";
        </script>
        <?php
    } else {
        die("Error inserting record: " . mysqli_error($conn));
    }
}
?>
<?php
if (isset($_POST['submit'])) {
    // Ensure the file has been uploaded
    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
        // Get the uploaded file's original name
        $file = $_FILES['fileToUpload'];
        $fileName = $file['name'];
        $tmpName = $file['tmp_name'];

        // Define the directory and file path where the file will be stored
        $filepath = "documents/" . basename($fileName);

        // Move the uploaded file to the documents directory
        if (move_uploaded_file($tmpName, $filepath)) {
            echo "File uploaded successfully!";
        } else {
            die("Error moving uploaded file.");
        }

        // Set the timestamp to the current date and time
        $timestamp = date('Y-m-d H:i:s');

        // Insert the form details into the database
        $sql = "INSERT INTO `forms` (`filename`, `filepath`, `timestamp`, `status`) 
                VALUES ('$fileName', '$filepath', '$timestamp', 'pending')";

        // Execute the query and check for errors
        if (mysqli_query($conn, $sql)) {
            echo "Form submitted successfully!";
        } else {
            echo "Error submitting form: " . mysqli_error($conn);
        }
    } else {
        echo "No file uploaded or there was an error during the upload process.";
    }
}

?>






        <script src="https://cdnjs.cloudflare.com/ajax/libs/jSignature/2.1.2/jSignature.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#signer1').jSignature();
                $('#submit-signature1').click(function() {
                    var signatureData = $('#signer1').jSignature('getData');
                    $.post('save_signature.php', { signature: signatureData, form_id: '<?php echo $formId; ?>' }, function(response) {
                        alert('Signature submitted');
                    });
                });
            });
        </script>




<script>
        const fileInput = document.getElementById('file-input');
        const imagePreview = document.getElementById('image-preview');
        const uploadButton = document.getElementById('upload-button');

fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
uploadButton.addEventListener('click', () => {
    const file = fileInput.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('scanned_form', file);


fetch('upload.php', {
    method: 'POST',
    body: formData
})
.then(response => response.text())
.then(result => {
    alert(result);
})
.catch(error => {
    console.error('Error:', error);
});
} else {
alert('Please select a file.');
}
});
</script>
</body>
</html>




        <a href="logout.php">Logout</a>
    </div>
</body>
</html>


