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

// Initialize variables
$searchName = '';

// Check if the search form is submitted
if (isset($_POST['searchName'])) {
    $searchName = $_POST['searchName'];
}

// Query to select all rows from the tb_orders table (one product per order per customer)
$sql = "SELECT customer_name, product_name, quantity, order_date FROM tb_orders";
if ($searchName) {
    // Add WHERE clause to filter orders by customer name
    $sql .= " WHERE customer_name LIKE '%" . $conn->real_escape_string($searchName) . "%'";
}

$result = $conn->query($sql);

// Initialize totals
$totalOrders = 0;
$totalQuantity = 0;

// Fetch data from the database and calculate totals
while ($row = $result->fetch_assoc()) {
    $totalOrders++;
    $totalQuantity += $row['quantity']; // Ensure 'quantity' column exists
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - GSL25 Inventory Management System</title>
    <link rel="icon" href="img/GSL25_transparent 2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        /* Body and general styling */
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            margin: 0;
            color: #2c3e50;
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
        }

        .mainHeader h1 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .headerActions .button {
            background-color: #3498db;
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .headerActions .button i {
            margin-right: 8px;
        }

        .headerActions .button:hover {
            background-color: #2980b9;
        }

        .ordersTable {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .ordersTable th, .ordersTable td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .ordersTable th {
            background-color: #3498db;
            color: #ffffff;
        }

        .ordersTable tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .dark-mode .ordersTable th {
            background-color: #2980b9;
        }

        .dark-mode .ordersTable tr:nth-child(even) {
            background-color: #34495e;
        }

        @media (max-width: 768px) {
            .mainContent {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Add your CSS styles here */
        .search-bar {
            margin-bottom: 20px;
            text-align: right;
            float: right;
            margin-right: 30px; /* Add margin to the right if needed */
        }

        .search-bar input {
            padding: 8px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            width: 200px;
            font-size: 1rem;
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
        <header class="mainHeader">
            <h1>Order Reports</h1>
            <div class="headerActions">
                <!-- You can add a button here for adding new orders if needed -->
                <!-- <a href="add-order.php" class="button"><i class="fa fa-plus"></i> Add New Order</a> -->
            </div>
        </header>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="POST" action="">
                <input type="text" name="searchName" value="<?php echo htmlspecialchars($searchName); ?>" placeholder="Search by Customer Name">
                <button type="submit" class="button"><i class="fa fa-search"></i> Search</button>
            </form>
        </div>

        <!-- Totals Section -->
        <section class="totalsSection mb-6">
            <h2>Total Orders: <?php echo $totalOrders; ?></h2>
            <h2>Total Quantity: <?php echo $totalQuantity; ?></h2>
        </section>

        <!-- Orders Section -->
        <section class="ordersSection">
            <?php 
            // Reset the pointer of the result set to fetch data again for the table
            $result->data_seek(0);
            $currentCustomer = '';
            
            while($row = $result->fetch_assoc()) {
                // If the customer name has changed, display it above their orders
                if ($row['customer_name'] !== $currentCustomer) {
                    // If we already have orders for a previous customer, we can close that section
                    if ($currentCustomer !== '') {
                        echo '</tbody></table>';
                    }
                    
                    // Update the current customer and display the name with the orders
                    $currentCustomer = $row['customer_name'];
                    $orderDate = date("F j, Y", strtotime($row['order_date'])); // Format the order date
                    echo "<h3>Customer: " . htmlspecialchars($currentCustomer) . "</h3>";
                    echo "<p>Order Date: " . $orderDate . "</p>";
                    echo "<table class='ordersTable'>
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>";
                }
            ?>
                <tr>
                    <td><?php echo isset($row['product_name']) ? htmlspecialchars($row['product_name']) : 'N/A'; ?></td>
                    <td><?php echo isset($row['quantity']) ? htmlspecialchars($row['quantity']) : 'N/A'; ?></td>
                </tr>
            <?php 
            }
            // Close the last table after the loop finishes
            echo '</tbody></table>';
            ?>
        </section>
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
    </script>
</body>
</html>