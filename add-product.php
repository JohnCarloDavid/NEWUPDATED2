<?php
if (isset($_POST['submit'])) {
    include('db_connection.php');

    $name = $_POST['name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    
    // Handle the uploaded image file
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    
    // Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Prepare the SQL query
        $sql = "INSERT INTO tb_inventory (name, category, quantity, size, price, image_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Bind the parameters (note the number of parameters matches the SQL statement)
        $stmt->bind_param('ssisss', $name, $category, $quantity, $size, $price, $target_file);

        if ($stmt->execute()) {
            $message = "<p class='message success'>New product added successfully.</p>";
        } else {
            $message = "<p class='message error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        $message = "<p class='message error'>Error uploading image.</p>";
    }
    
    $conn->close();
}?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - GSL25 Inventory Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="icon" href="img/GSL25_transparent 2.png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #000000, gray, #ffffff); /* Consistent gradient */
            margin: 0;
            padding: 0;
            color: #333; /* General text color */
            transition: background-color 0.3s, color 0.3s;
        }
        body.dark-mode {
            background: linear-gradient(to right, #2c3e50, #34495e, #2c3e50); /* Dark gradient */
            color: #ecf0f1; /* Light text color */
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff; /* White background for container */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: background 0.3s;
        }
        .container.dark-mode {
            background: #34495e; /* Dark container background */
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        form input[type="text"],
        form input[type="number"],
        form input[type="date"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            color: #000; /* Black text color */
            background-color: #ffffff; /* White background for input fields */
        }
        body.dark-mode form input[type="text"],
        body.dark-mode form input[type="number"],
        body.dark-mode form input[type="date"],
        body.dark-mode form select {
            color: #ffffff; /* White text color for dark mode */
            background-color: #34495e; /* Dark background color for dark mode */
        }
        .button-container {
            display: flex;
            justify-content: flex-start; /* Align buttons to the start of the container */
            margin-top: 20px;
        }
        .button-container a,
        .button-container input[type="submit"] {
            margin: 0;
            margin-right: 10px; /* Add margin between buttons */
        }
        .button-container input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .button-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .button-container a {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            display: inline-block;
            text-decoration: none;
            margin-left: 465px;
        }
        .button-container a:hover {
            background-color: #ff0000;
            color: #ffffff;
            border: 1px solid #ff0000;
        }
        .dark-mode-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            z-index: 1000;
        }
        .dark-mode-toggle:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-2xl font-bold mb-4">Add New Product</h1>
        <?php
        if (isset($message)) {
            echo $message;
        }
        ?>
        <form action="add-product.php" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>
            
            <label for="size">Size:</label>
            <input type="text" id="size" name="size" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <div class="button-container">
                <input type="submit" name="submit" value="Add Product">
                <a href="inventory.php" class="back-button">Back to Inventory</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
                document.querySelector('.container').classList.add('dark-mode');
            }
        });

        function toggleDarkMode() {
            const body = document.body;
            const container = document.querySelector('.container');
            body.classList.toggle('dark-mode');
            container.classList.toggle('dark-mode');

            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        }
    </script>
</body>
</html>
