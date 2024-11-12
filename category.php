<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');

// Check if the user is logged in, if not then redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Initialize the category
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Initialize the search term
$search = isset($_GET['search']) ? '%' . trim($_GET['search']) . '%' : '%';

// Query to select products for the given category and search term, including price
$sql = "SELECT product_id, name, price, size, quantity
        FROM tb_inventory 
        WHERE category = ? AND (product_id LIKE ? OR name LIKE ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $category, $search, $search);
$stmt->execute();
$result = $stmt->get_result();

// Query to calculate the total stock for the given category
$total_stock_sql = "SELECT SUM(quantity) AS total_stock 
                    FROM tb_inventory 
                    WHERE category = ? AND (product_id LIKE ? OR name LIKE ?)";
$total_stock_stmt = $conn->prepare($total_stock_sql);
$total_stock_stmt->bind_param('sss', $category, $search, $search);
$total_stock_stmt->execute();
$total_stock_result = $total_stock_stmt->get_result();
$total_stock = $total_stock_result->fetch_assoc()['total_stock'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category); ?> - GSL25 Inventory Management System</title>
    <link rel="icon" href="img/GSL25_transparent 2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        /* Common body and general styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            color: #2c3e50;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align content to the top */
            height: 100vh;
        }

        .dark-mode {
            background-color: #2c3e50;
            color: #ecf0f1; 
        }

        .container {
            width: 90%;
            max-width: 1200px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px; /* Add margin to push content down from top */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dark-mode .container {
            background-color: #34495e;
        }

        .mainHeader {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2rem;
        }

        .mainHeader h1 {
            font-size: 2.5rem;
            margin: 0;
            color: #3498db;
        }

        .toggleButton {
            background-color: #3498db;
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            outline: none;
            transition: background-color 0.3s ease;
            margin: 10px auto;
            display: block;
            width: 200px;
            text-align: center;
        }

        .toggleButton:hover {
            background-color: #2980b9;
        }

        .backButton {
            background-color: #e74c3c;
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            outline: none;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
            display: block;
            width: 200px;
            text-align: center;
        }

        .backButton:hover {
            background-color: #c0392b;
        }

        .inventoryTable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }

        .inventoryTable th, .inventoryTable td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .inventoryTable th {
            background-color: #3498db;
            color: #ffffff;
        }

        .inventoryTable td {
            background-color: #ffffff;
            color: #2c3e50;
        }

        .dark-mode .inventoryTable th {
            background-color: #2980b9;
            color: #ecf0f1;
        }

        .dark-mode .inventoryTable td {
            background-color: #34495e;
            color: #ecf0f1;
        }

        .totalStockFooter {
            margin-top: 2rem;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
        }

        /* Search Bar */
        .searchContainer {
            position: relative;
            width: 100%; /* Adjust width to fit the container */
            display: flex; /* Align input and button in a row */
            align-items: center; /* Center-align the content */
        }

        .searchInput {
            flex-grow: 1; /* Allow the input to take up remaining space */
            padding: 8px; /* Padding inside the input */
            border-radius: 8px 0 0 8px; /* Rounded corners on the left side */
            border: 1px solid #ddd;
            font-size: 14px; /* Font size */
            color: #000; /* Text color */
            margin-right: -1px; /* Overlap border with button */
        }

        .searchClear {
            position: absolute;
            right: 80px; /* Position relative to input */
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px; /* Font size */
            color: #000;
            display: none; /* Hidden by default */
        }

        .searchClear:hover {
            color: #888;
        }

        .searchButton {
            background-color: #3498db;
            color: #ffffff;
            padding: 8px 12px; /* Padding inside the button */
            border-radius: 0 8px 8px 0; /* Rounded corners on the right side */
            cursor: pointer;
            border: 1px solid #ddd;
            border-left: none; /* No left border to align with input */
            font-size: 14px; /* Font size */
            outline: none;
            transition: background-color 0.3s ease;
            height: 100%; /* Make button height match input */
        }

        .quantity-actions .PLUS {
    color: green;
    font-size: 1.5rem; /* Adjust size */
}

.quantity-actions .PLUS:hover {
    color: #ffffff;
    font-size: 1.5rem; /* Slightly increase size on hover */
}

.quantity-actions .MINUS {
    color: #c0392b;
    font-size: 1.5rem; /* Adjust size */
}

.quantity-actions .MINUS:hover {
    color: #ffffff;
    font-size: 1.5rem; /* Slightly increase size on hover */
}

    </style>
</head>
<body>
    <div class="container">
        <div class="mainHeader">
            <h1><?php echo htmlspecialchars($category); ?> Inventory</h1>
        </div>
        <a href="inventory.php" class="backButton">Back to Inventory</a>

        <!-- Search Bar -->
        <form method="GET" action="">
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <div class="searchContainer">
                <input type="text" name="search" placeholder="Search products by ID or name..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="searchInput">
                <span class="searchClear">&times;</span>
                <button type="submit" class="searchButton">Search</button>
            </div>
        </form>

        <table class="inventoryTable">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['product_id']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['price']); ?></td>
            <td><?php echo htmlspecialchars($row['size']); ?></td>
            <td style="color: 
                <?php 
                    if ($row['quantity'] < 15) {
                        echo 'red';
                    } elseif ($row['quantity'] >= 15) {
                        echo 'green';
                    } else {
                        echo '#2c3e50';
                    }
                ?>;">
                <?php echo htmlspecialchars($row['quantity']); ?>
            </td>
            <td class="quantity-actions flex items-center justify-center space-x-4">
                <!-- Quantity Adjustments -->
                <i class="PLUS fas fa-plus-circle text-green-500 cursor-pointer hover:text-green-600" data-action="increase" data-id="<?php echo htmlspecialchars($row['product_id']); ?>"></i>
                <i class="MINUS fas fa-minus-circle text-red-500 cursor-pointer hover:text-red-600" data-action="decrease" data-id="<?php echo htmlspecialchars($row['product_id']); ?>"></i>

                <!-- Edit and Delete Buttons -->
                <a href="edit_product.php?product_id=<?php echo htmlspecialchars($row['product_id']); ?>" class="editButton flex items-center justify-center p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors duration-200" title="Edit Product">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="delete_product.php?product_id=<?php echo htmlspecialchars($row['product_id']); ?>" class="deleteButton flex items-center justify-center p-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors duration-200" onclick="return confirm('Are you sure you want to delete this product?');" title="Delete Product">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

        </table>
        <div class="totalStockFooter">
            Total Stock: <?php echo $total_stock; ?>
        </div>
    </div>

    <!-- Dark Mode Toggle Script -->
    <script>
       document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
            }
        });

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        // Show/Hide clear button based on input value
        document.querySelector('.searchInput').addEventListener('input', function() {
            var clearButton = document.querySelector('.searchClear');
            if (this.value.length > 0) {
                clearButton.style.display = 'block';
            } else {
                clearButton.style.display = 'none';
            }
        });

        // Clear input field when clear button is clicked
        document.querySelector('.searchClear').addEventListener('click', function() {
            var searchInput = document.querySelector('.searchInput');
            searchInput.value = '';
            searchInput.focus();
            searchInput.dispatchEvent(new Event('input')); // Trigger input event to hide clear button
        });
    </script>

    <!-- AJAX Script -->
    <script>
        document.querySelectorAll('.quantity-actions i').forEach(icon => {
            icon.addEventListener('click', function () {
                const action = this.getAttribute('data-action');
                const productId = this.getAttribute('data-id');
                
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_quantity.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (this.status === 200) {
                        location.reload(); // Refresh the page to reflect the changes
                    } else {
                        console.error('Failed to update quantity');
                    }
                };
                xhr.send(`product_id=${productId}&action=${action}`);
            });
        });
    </script>
</body>
</html>
