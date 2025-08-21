<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: forms.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>NEW USER</h2>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="presetpassword">Preset Password</label>
                <input type="password" id="presetpassword" name="presetpassword" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit">Submit</button>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "signin"; // Replace with your database name

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get form data
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $presetpassword = $_POST['presetpassword'];
            $role = $_POST['role'];
            $password = $_POST['role'];
            $changepassword = $password;

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO signin (firstname, lastname, presetpassword,changedpassword, role) VALUES (?, ?, ?,?, ?)");
            $stmt->bind_param("sssss",$firstname, $lastname, $presetpassword,$changepassword, $role);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<p class='success'>New record created successfully</p>";
                header('Location: protectedadmin.php'); // Redirect to a protected page
        exit();
            } else {
                echo "<p class='error'>Error: " . $stmt->error . "</p>";
            }

            // Close connection
            $stmt->close();
            $conn->close();
        }
        ?>


    </div>
</body>
</html>

