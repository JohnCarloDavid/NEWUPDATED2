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

// Initialize the search term
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query to select all rows from the tb_inventory table and group by category
$sql = "SELECT category, GROUP_CONCAT(product_id, '::', name, '::', quantity SEPARATOR ';;') AS products, 
               SUM(quantity) AS total_quantity
        FROM tb_inventory";
if (!empty($search)) {
    $sql .= " WHERE name LIKE '%$search%' OR category LIKE '%$search%' OR product_id LIKE '%$search%'";
}
$sql .= " GROUP BY category";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - GSL25 Inventory Management System</title>
    <link rel="icon" href="img/GSL25_transparent 2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            color: #2c3e50;
            background-color: #ecf0f1;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dark-mode {
            background-color: #2c3e50;
            color: #ecf0f1;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(145deg, #34495e, #2c3e50);
            color: #ecf0f1;
            padding: 30px 20px;
            height: 100vh;
            position: fixed;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease;
        }

        .sidebarHeader h2 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .sidebarNav ul {
            list-style: none;
            padding: 0;
        }

        .sidebarNav ul li {
            margin: 1.2rem 0;
        }

        .sidebarNav ul li a {
            text-decoration: none;
            color: #ecf0f1;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .sidebarNav ul li a:hover {
            background-color: #2980b9;
        }

        .sidebarNav ul li a i {
            margin-right: 15px;
        }

        .mainContent {
            margin-left: 280px;
            padding: 30px;
            width: calc(100% - 280px);
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .mainHeader {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .mainHeader h1 {
            font-size: 2rem;
            margin: 0;
        }

        .searchForm {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .searchInput {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
            width: 250px;
            color: #2c3e50;
        }

        .searchButton, .clearButton {
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            outline: none;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            margin-left: 5px;
        }

        .searchButton {
            background-color: #3498db;
            color: #ffffff;
        }

        .searchButton:hover {
            background-color: #2980b9;
        }

        .clearButton {
            background-color: #e74c3c;
            color: #ffffff;
        }

        .clearButton:hover {
            background-color: #c0392b;
        }

        .categoryButton {
            width: 100%;
            background-color: #2980b9;
            color: #ffffff;
            padding: 15px;
            margin-top: 10px;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            font-size: 1.2rem;
            border: none;
            outline: none;
            transition: background-color 0.3s ease;
            display: block;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .categoryButton:hover {
            background-color: #3498db;
        }

        .categoryContainer {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .low-stock {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebarHeader">
            <h2>GSL25 Dashboard</h2>
        </div>
        <nav class="sidebarNav">
            <ul>
                <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="inventory.php"><i class="fa fa-box"></i> Inventory</a></li>
                <li><a href="orders.php"><i class="fa fa-receipt"></i> Orders</a></li>
                <li><a href="reports.php"><i class="fa fa-chart-line"></i> Reports</a></li>
                <li><a href="settings.php"><i class="fa fa-cog"></i> Settings</a></li>
            </ul>
        </nav>
    </aside>
<!-- Main Content -->
<div class="mainContent">
    <div class="mainHeader">
        <h1>Inventory</h1>
        <div class="flex space-x-4">
            <a href="add-product.php" class="bg-blue-500 text-white py-2 px-4 rounded-lg shadow-lg hover:bg-blue-600 transition-colors duration-300">Add Product</a>
            <a href="pos.php" class="bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg hover:bg-green-600 transition-colors duration-300">Point of Sale</a> <!-- POS Button -->
        </div>
    </div>
    <form method="GET" action="inventory.php" class="searchForm">
        <input type="text" name="search" class="searchInput" placeholder="Search Category..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="searchButton">Search</button>
        <?php if (!empty($search)): ?>
            <button type="button" onclick="clearSearch()" class="clearButton">Clear</button>
        <?php endif; ?>
    </form>
    <div class="categoryContainer">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <?php
                    // Check if the category has low stock items
                    $hasLowStock = false;
                    $products = explode(';;', $row['products']);
                    foreach ($products as $product) {
                        list($product_id, $name, $quantity) = explode('::', $product);
                        if ($quantity < 15) {
                            $hasLowStock = true;
                            break;
                        }
                    }
                ?>
                <a href="category.php?category=<?php echo urlencode($row['category']); ?>" class="categoryButton <?php echo $hasLowStock ? 'low-stock' : ''; ?>">
                    <?php echo htmlspecialchars($row['category']); ?>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>
</div>

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

        function clearSearch() {
            window.location.href = 'inventory.php';
        }
    </script>
</body>
</html>