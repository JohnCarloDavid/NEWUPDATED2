<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Initialize variables
$selected_date = '';
$searchQuery = ''; // Variable for the search query

// Check if a date filter has been applied
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_date'])) {
    $selected_date = $_POST['selected_date'];
}

// Check if a search query has been entered
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Fetch orders from the database based on the selected date or search query
if ($selected_date && $searchQuery) {
    $sql = "SELECT o.*, i.size, i.price FROM tb_orders o 
            JOIN tb_inventory i ON o.product_name = i.name 
            WHERE o.order_date = ? AND o.customer_name LIKE ? 
            ORDER BY o.order_id ASC";
    $stmt = $conn->prepare($sql);
    $searchParam = "%" . $searchQuery . "%";
    $stmt->bind_param('ss', $selected_date, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif ($selected_date) {
    $sql = "SELECT o.*, i.size, i.price FROM tb_orders o 
            JOIN tb_inventory i ON o.product_name = i.name 
            WHERE o.order_date = ? ORDER BY o.order_id ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $selected_date);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif ($searchQuery) {
    $sql = "SELECT o.*, i.size, i.price FROM tb_orders o 
            JOIN tb_inventory i ON o.product_name = i.name 
            WHERE o.customer_name LIKE ? ORDER BY o.order_id ASC";
    $stmt = $conn->prepare($sql);
    $searchParam = "%" . $searchQuery . "%";
    $stmt->bind_param('s', $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default query if no filters are applied
    $sql = "SELECT o.*, i.size, i.price FROM tb_orders o 
            JOIN tb_inventory i ON o.product_name = i.name 
            ORDER BY o.order_id ASC";
    $result = $conn->query($sql);
}

// Calculate the total number of orders
$total_orders = 0;
if ($result) {
    $total_orders = $result->num_rows;
}

// Initialize variables for total amount and total quantity
$total_amount = 0;
$total_quantity = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - GSL25 Inventory Management System</title>
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

        .headerActions .button i {
            margin-right: 8px;
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

        .button {
            background-color: #ffffff; 
            color: #c0392b; 
            padding: 5px 10px; 
            border-radius: 8px;
            font-size: 1rem;
            text-align: center;
            text-decoration: none;
            border: 1px solid #3498db; 
            transition: background-color 0.3s ease, color 0.3s ease;
            display: inline-block; 
            cursor: pointer; 
        }

        .button:hover {
            background-color: #3498db;
            color: #ffffff; 
        }

        .totalOrders {
            margin-top: 30px;
            font-size: 1.2rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .mainContent {
                margin-left: 0;
                width: 100%;
            }
        }

        .mainHeader {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.headerActions {
    display: flex;
    align-items: center;
}

.headerActions .button {
    margin-right: 10px;
    padding: 8px 15px;
    background-color: #4CAF50;
    color: white;
    border-radius: 5px;
    text-decoration: none;
}

.headerActions .button:hover {
    background-color: #45a049;
}

/* Search Form Styling */
.search-form {
    display: flex;
    align-items: center;
    margin-left: 20px; /* Space between buttons and search bar */
    position: relative;
}

.search-input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 250px;
    font-size: 14px;
    outline: none;
}

.search-button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 5px;
}

.search-button:hover {
    background-color: #45a049;
}

.search-input:focus {
    border-color: #4CAF50;
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

    <div class="mainContent">
        <header class="mainHeader">
            <h1>Order Management</h1>
            <div class="headerActions">
                <a href="add-order.php" class="button"><i class="fa fa-plus"></i> Add New Order</a>
                <a href="recently-deleted.php" class="button"><i class="fa fa-undo"></i> Recently Deleted</a>
                <!-- Search Bar -->
                <form action="orders.php" method="GET" class="search-form">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search by customer name" class="search-input" />
                    <button type="submit" class="search-button"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </header>
        <!-- Total Orders and Total Quantity -->
        <section class="orderTotals">
            <p>Total Orders: <?php echo $total_orders; ?></p>
        </section>

        <!-- Orders Table -->
        <section class="ordersSection">
            <table class="ordersTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Initialize a variable to store the last customer name
                    $lastCustomerName = "";
                    $customerTotalAmount = 0; // Variable to store total amount per customer
                    
                    // Display the fetched orders
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { 
                            $total_quantity += $row['quantity'];
                            $total_price = $row['quantity'] * $row['price']; // Calculate total price for the order
                            $total_amount += $total_price; // Add to the total amount
                            
                            if ($row['customer_name'] != $lastCustomerName) {
                                if ($lastCustomerName != "") {
                                    // Display the total amount for the previous customer
                                    echo '<tr><td colspan="6"><strong>Total Amount: ' . number_format($customerTotalAmount, 2) . '</strong></td></tr>';
                                    echo '<tr><td colspan="6">&nbsp;</td></tr>'; // Add space between customers
                                }
                                // Reset the customer total for the new customer
                                $customerTotalAmount = 0;
                                $lastCustomerName = $row['customer_name'];
                    ?>
                        <!-- Customer Name Row -->
                        <tr class="customer-row">
                            <td colspan="6" class="customer-name">
                                <strong><?php echo htmlspecialchars($row['customer_name']); ?></strong>
                            </td>
                        </tr>
                        <!-- Order Date Row -->
                        <tr class="order-date-row">
                            <td colspan="6" class="order-date">
                                <strong>Order Date: <?php echo htmlspecialchars($row['order_date']); ?></strong>
                            </td>
                        </tr>
                    <?php
                            }
                            
                            // Add to the customer's total amount
                            $customerTotalAmount += $total_price;
                    ?>
                        <!-- Order Details Row -->
                        <tr class="order-row">
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['size']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['price'], 2)); ?></td>
                            <td><?php echo htmlspecialchars(number_format($total_price, 2)); ?></td>
                            <td>
                                <a href="edit-order.php?id=<?php echo htmlspecialchars($row['order_id']); ?>" class="button"><i class="fa fa-edit"></i></a>
                                <a href="delete-order.php?id=<?php echo htmlspecialchars($row['order_id']); ?>" class="button" onclick="return confirm('Are you sure you want to delete this order?');"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php
                        }
                        // Display the total amount for the last customer
                        echo '<tr><td colspan="6"><strong>Total Amount: ' . number_format($customerTotalAmount, 2) . '</strong></td></tr>';
                    } else {
                        echo '<tr><td colspan="7">No orders found.</td></tr>';
                    }
                    ?>
                    <!-- Order Summary -->
                    <section class="orderSummary">
                        <p>Total Quantity: <?php echo $total_quantity; ?></p>
                        <p>Total Amount: <?php echo number_format($total_amount, 2); ?></p>
                    </section>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.querySelector('.search-form');
            searchForm.addEventListener('submit', function(event) {
                const searchInput = document.querySelector('.search-input').value;
                if (!searchInput.trim()) {
                    event.preventDefault();
                    alert('Please enter a customer name to search.');
                }
            });
        });
    </script>
</body>
</html>
