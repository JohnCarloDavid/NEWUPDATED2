<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');

// Check if the user is logged in
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

// Query to select all orders along with size and price from inventory
$sql = "SELECT o.customer_name, o.order_date, o.product_name, o.quantity, i.size, i.price 
        FROM tb_orders o 
        JOIN tb_inventory i ON o.product_name = i.name";

if (!empty($searchName)) {
    // Add a WHERE clause to filter orders by customer name
    $sql .= " WHERE o.customer_name LIKE '%" . $conn->real_escape_string($searchName) . "%'";
}

$sql .= " ORDER BY o.customer_name, o.order_date ASC";

$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Initialize totals
$totalOrders = 0;
$totalQuantity = 0;
$totalAmount = 0;

// Calculate totals
while ($row = $result->fetch_assoc()) {
    $totalOrders++;
    $totalQuantity += $row['quantity'];
    $totalAmount += $row['quantity'] * $row['price'];
}

// Reset result pointer for looping again
$result->data_seek(0);
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
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            color: #2c3e50;
            background-color: #f9f9f9;
        }

        .dark-mode {
            background-color: #2c3e50;
            color: #ecf0f1;
        }

        /* Sidebar Styling */
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

        /* Main Content Styling */
        .mainContent {
            margin-left: 280px;
            padding: 30px;
            width: calc(100% - 280px);
        }

        .mainHeader h1 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #34495e;
        }

        .totalsSection {
            display: flex;
            justify-content: space-around;
            margin-bottom: 2rem;
            background: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .totalsSection div {
            text-align: center;
        }

        .totalsSection div h2 {
            font-size: 1.5rem;
            color: #3498db;
            margin: 0;
        }

        .totalsSection div span {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .ordersSection h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-size: 1.8rem;
            color: #34495e;
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
            background-color: #f9f9f9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .mainContent {
                margin-left: 0;
                width: 100%;
            }

            .totalsSection {
                flex-direction: column;
            }

            .totalsSection div {
                margin-bottom: 10px;
            }
        }
        .dark-mode .ordersTable th {
            background-color: #2980b9;
        }

        .dark-mode .ordersTable tr:nth-child(even) {
            background-color: #34495e;
        }

        .dark-mode .mainHeader h1 {
            color: white;
            background-color: transparent; 
        }
        .dark-mode h3 {
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
        <header class="mainHeader">
            <h1 >Order Reports</h1>
        </header>

        <!-- Totals Section -->
        <section class="totalsSection">
            <div>
                <h2>Total Orders</h2>
                <span><?php echo $totalOrders; ?></span>
            </div>
            <div>
                <h2>Total Quantity</h2>
                <span><?php echo $totalQuantity; ?></span>
            </div>
            <div>
                <h2>Total Amount</h2>
                <span>₱<?php echo number_format($totalAmount, 2); ?></span>
            </div>
        </section>

        <!-- Orders Section -->
        <section class="ordersSection">
            <?php
            $currentCustomer = '';
            $currentTotal = 0;

            while ($row = $result->fetch_assoc()) {
                $orderTotal = $row['quantity'] * $row['price'];

                if ($row['customer_name'] !== $currentCustomer) {
                    if ($currentCustomer !== '') {
                        echo "<tfoot><tr><td colspan='4'>Subtotal</td><td>₱" . number_format($currentTotal, 2) . "</td></tr></tfoot>";
                        echo '</tbody></table>';
                    }

                    $currentCustomer = $row['customer_name'];
                    $currentTotal = 0;

                    echo "<h3>Customer: " . htmlspecialchars($currentCustomer) . "</h3>";
                    echo "<p>Order Date: " . date("F j, Y", strtotime($row['order_date'])) . "</p>";
                    echo "<table class='ordersTable'>
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Size</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>";
                }

                $currentTotal += $orderTotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['size']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td>₱<?php echo number_format($row['price'], 2); ?></td>
                    <td>₱<?php echo number_format($orderTotal, 2); ?></td>
                </tr>
                <?php
            }

            if ($currentCustomer !== '') {
                echo "<tfoot><tr><td colspan='4'>Subtotal</td><td>₱" . number_format($currentTotal, 2) . "</td></tr></tfoot>";
                echo '</tbody></table>';
            }
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

        function clearSearch() {
            window.location.href = 'inventory.php';
        }
    </script>
</body>
</html>
