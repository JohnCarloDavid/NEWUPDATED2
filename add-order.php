<?php
// Start the session
session_start();

// Include database connection file
include('db_connection.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $order_date = $_POST['order_date'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Iterate over products to process each item in the order
        foreach ($_POST['product_name'] as $index => $product_name) {
            $size = $_POST['size'][$index];
            $quantity = $_POST['quantity'][$index];
            $status = isset($_POST['status'][$index]) ? $_POST['status'][$index] : 'Pending';

            // Insert each product into the orders table
            $sql = "INSERT INTO tb_orders (customer_name, product_name, size, quantity, order_date, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssiss', $customer_name, $product_name, $size, $quantity, $order_date, $status);

            if (!$stmt->execute()) {
                throw new Exception("Error adding order.");
            }

            // Retrieve and update inventory quantity
            $inventory_sql = "SELECT quantity FROM tb_inventory WHERE name = ? AND size = ?";
            $inventory_stmt = $conn->prepare($inventory_sql);
            $inventory_stmt->bind_param('ss', $product_name, $size);
            $inventory_stmt->execute();
            $inventory_result = $inventory_stmt->get_result();

            if ($inventory_result->num_rows === 0) {
                throw new Exception("Product not found in inventory.");
            }

            $inventory_row = $inventory_result->fetch_assoc();
            $current_quantity = $inventory_row['quantity'];

            if ($current_quantity < $quantity) {
                throw new Exception("Not enough stock for $product_name ($size).");
            }

            $new_quantity = $current_quantity - $quantity;
            $update_inventory_sql = "UPDATE tb_inventory SET quantity = ? WHERE name = ? AND size = ?";
            $update_inventory_stmt = $conn->prepare($update_inventory_sql);
            $update_inventory_stmt->bind_param('iss', $new_quantity, $product_name, $size);

            if (!$update_inventory_stmt->execute()) {
                throw new Exception("Error updating inventory.");
            }
        }

        $conn->commit();
        header('Location: orders.php');
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('" . $e->getMessage() . "'); window.location.href = 'add-order.php';</script>";
    }
}

$product_sql = "SELECT DISTINCT name FROM tb_inventory";
$product_result = $conn->query($product_sql);

$size_sql = "SELECT name, size FROM tb_inventory";
$size_result = $conn->query($size_sql);

$product_sizes = [];
while ($row = $size_result->fetch_assoc()) {
    $product_name = $row['name'];
    $size = $row['size'];
    if (!isset($product_sizes[$product_name])) {
        $product_sizes[$product_name] = [];
    }
    $product_sizes[$product_name][] = $size;
}
$product_sizes_json = json_encode($product_sizes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Order - GSL25 Inventory Management System</title>
    <link rel="icon" href="img/GSL25_transparent 2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #000000, gray, #ffffff);
            color: #333;
            transition: background-color 0.3s, color 0.3s;
        }
        body.dark-mode {
            background: #2c3e50;
            color: #ecf0f1;
        }

        /* Container Styles */
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: background 0.3s, color 0.3s;
        }
        .container.dark-mode {
            background: #34495e;
        }

        /* Header */
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #007bff;
        }

        /* Form Element Styles */
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
            color: #333;
            background-color: #ffffff;
            transition: background-color 0.3s, color 0.3s;
        }
        body.dark-mode form input[type="text"],
        body.dark-mode form input[type="number"],
        body.dark-mode form input[type="date"],
        body.dark-mode form select {
            color: #ffffff;
            background-color: #2c3e50;
            border: 1px solid #444;
        }

        /* Button Styles */
        button, .backButton {
            padding: 10px 15px;
            border-radius: 4px;
            font-size: 16px;
            display: inline-block;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
        }
        button:hover {
            background-color: #0056b3;
        }
        .backButton {
            background-color: #e74c3c;
            color: #ffffff;
        }
        .backButton:hover {
            background-color: red;
        }

        /* Product Entry Styles */
        .product-entry {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            background-color: #f9f9f9;
            position: relative;
        }
        .product-entry button.remove-product {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #e74c3c;
            color: white;
            padding: 5px;
            border-radius: 4px;
        }
        .product-entry button.remove-product:hover {
            background-color: #c0392b;
        }
        body.dark-mode .product-entry {
            background-color: #3b3b3b;
        }

        /* Dark Mode Toggle */
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
        }
        .dark-mode-toggle:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-2xl font-bold mb-4">Add New Order</h1>
    <form action="add-order.php" method="POST">
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required>

        <label for="order_date">Order Date:</label>
        <input type="date" id="order_date" name="order_date" required>

        <div id="products-container">
            <div class="product-entry">
                <label for="product_name">Product Name:</label>
                <select name="product_name[]" required onchange="updateSizes(this)">
                    <?php while ($product_row = $product_result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($product_row['name']); ?>">
                            <?php echo htmlspecialchars($product_row['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="size">Size:</label>
                <select name="size[]" required></select>

                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity[]" required>

                <button type="button" class="remove-product" onclick="removeProductEntry(this)">Remove</button>
            </div>
        </div>

        <button type="button" onclick="addProductEntry()">Add Another Product</button>

        <div class="button-container mt-6">
            <button type="submit">Save Order</button>
            <a href="orders.php" class="backButton">Back to Orders</a>
        </div>
    </form>
</div>

<script>
    const productSizes = <?php echo $product_sizes_json; ?>;

    function updateSizes(selectElement) {
        const selectedProduct = selectElement.value;
        const productEntry = selectElement.closest('.product-entry');
        const sizeSelect = productEntry.querySelector('select[name="size[]"]');
        sizeSelect.innerHTML = '';
        (productSizes[selectedProduct] || []).forEach(size => {
            const option = document.createElement('option');
            option.value = size;
            option.textContent = size;
            sizeSelect.appendChild(option);
        });
    }

    function addProductEntry() {
        const container = document.getElementById('products-container');
        const newEntry = document.querySelector('.product-entry').cloneNode(true);
        newEntry.querySelector('select[name="product_name[]"]').value = "";
        newEntry.querySelector('select[name="size[]"]').innerHTML = "";
        newEntry.querySelector('input[name="quantity[]"]').value = "";
        container.appendChild(newEntry);
    }

    function removeProductEntry(button) {
        const container = document.getElementById('products-container');
        if (container.children.length > 1) {
            button.closest('.product-entry').remove();
        } else {
            alert("At least one product must be included in the order.");
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelector('select[name="product_name[]"]').dispatchEvent(new Event('change'));
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            document.querySelector('.container').classList.add('dark-mode');
        }
    });

    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        document.querySelector('.container').classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
    }
</script>

</body>
</html>
