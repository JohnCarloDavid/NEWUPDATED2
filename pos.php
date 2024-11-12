<?php
// Start session and include database connection
session_start();
include('db_connection.php');

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Check if the user is logged in and is an admin
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

// Initialize search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL query to fetch products, applying search filter if provided
$query = "SELECT * FROM tb_inventory WHERE quantity > 0";
if ($search) {
    $search = $conn->real_escape_string($search); // Prevent SQL injection
    $query .= " AND name LIKE '%$search%'";
}

// Get all products for the POS system
$products = $conn->query($query);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - GSL25 Inventory Management System</title>
    <link rel="icon" href="img/GSL25_transparent 2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        /* Additional styles for improved receipt and product images */
        .receipt-table th,
        .receipt-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .receipt-table th {
            background-color: #f2f2f2;
        }
        .product-image {
            width: 50px; /* Adjust the size as needed */
            height: 50px; /* Adjust the size as needed */
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">
<div class="container mx-auto py-12 px-6">
    <div class="mb-6 flex justify-between items-center">
        <button onclick="window.location.href='inventory.php'" class="flex items-center bg-gray-800 hover:bg-gray-700 text-white py-2 px-4 rounded-lg shadow-md transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </button>
        <h1 class="text-4xl font-bold text-gray-800">Point of Sale (POS)</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product List Section -->
        <div class="bg-white p-6 rounded-lg shadow-lg h-96 overflow-y-auto">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Product List</h2>
            <form method="GET" class="mb-6 flex">
                <input type="text" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search product...">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg transition duration-300">Search</button>
            </form>
            
            <ul class="product-list space-y-4">
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/pr5.jpg" alt="Gi-pipes1" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Gi-pipes1 (Size: ½)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10060, 'Gi-pipes1', 260, '½', 'uploads/pr5.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/pr5.jpg" alt="Gi-pipes2" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Gi-pipes2 (Size: ¾)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10061, 'Gi-pipes2', 360, '¾', 'uploads/pr5.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/pr5.jpg" alt="Gi-pipes3" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Gi-pipes3 (Size: 1)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10062, 'Gi-pipes3', 480, '1', 'uploads/pr5.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/pr5.jpg" alt="Gi-pipes4" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Gi-pipes4 (Size: 1 1⁄4)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10063, 'Gi-pipes4', 540, '1 1⁄4', 'uploads/pr5.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/pr5.jpg" alt="Gi-pipes5" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Gi-pipes5 (Size: 1 ½)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10064, 'Gi-pipes5', 780, '1 ½', 'uploads/pr5.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/pr5.jpg" alt="Gi-pipes6" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Gi-pipes6 (Size: 2)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10065, 'Gi-pipes6', 1150, '2', 'uploads/pr5.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/flat4.jpg" alt="Flat Bar1" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Flat Bar1 (Size: 1)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10066, 'Flat Bar1', 250, '1', 'uploads/flat4.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/flat4.jpg" alt="Flat Bar2" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Flat Bar2 (Size: 1 ½)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10067, 'Flat Bar2', 390, '1 ½', 'uploads/flat4.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/flat4.jpg" alt="Flat Bar3" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Flat Bar3 (Size: 2)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10068, 'Flat Bar3', 460, '2', 'uploads/flat4.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/23.-SS-ANGLE-BAR.jpg" alt="Angle Bar1" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Angle Bar1 (Size: 1x1)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10069, 'Angle Bar1', 350, '1x1', 'uploads/23.-SS-ANGLE-BAR.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/23.-SS-ANGLE-BAR.jpg" alt="Angle Bar2" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Angle Bar2 (Size: 1½ x 1½)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10070, 'Angle Bar2', 480, '1½ x 1½', 'uploads/23.-SS-ANGLE-BAR.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/23.-SS-ANGLE-BAR.jpg" alt="Angle Bar3" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Angle Bar3 (Size: 2x2)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10071, 'Angle Bar3', 590, '2x2', 'uploads/23.-SS-ANGLE-BAR.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/images.jfif" alt="Angle Bar4 (Green)" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Angle Bar4 (Green) (Size: 2x2)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10072, 'Angle Bar4 (Green)', 700, '2x2', 'uploads/images.jfif')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/images.jfif" alt="Angle Bar5 (Green)" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Angle Bar5 (Green) (Size: 1x1)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10073, 'Angle Bar5 (Green)', 420, '1x1', 'uploads/images.jfif')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/images.jfif" alt="Angle Bar6 (Green)" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Angle Bar6 (Green) (Size: 1½ x 1½ )</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10074, 'Angle Bar6 (Green)', 580, '1½ x 1½ ', 'uploads/images.jfif')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/steel-purlins-min.jpg" alt="Purlins1 (1.2)" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Purlins1 (1.2) (Size: 2X3)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10075, 'Purlins1 (1.2)', 360, '2X3', 'uploads/steel-purlins-min.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/steel-purlins-min.jpg" alt="Purlins2 (1.5)" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Purlins2 (1.5) (Size: 2x3)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10076, 'Purlins2 (1.5)', 460, '2x3', 'uploads/steel-purlins-min.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/steel-purlins-min.jpg" alt="Purlins3 (1.2)" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Purlins3 (1.2) (Size: 2x4)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10077, 'Purlins3 (1.2)', 420, '2x4', 'uploads/steel-purlins-min.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/steel-purlins-min.jpg" alt="Purlins4 (1.5)" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Purlins4 (1.5) (Size: 2x4)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10078, 'Purlins4 (1.5)', 520, '2x4', 'uploads/steel-purlins-min.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/steel-purlins-min.jpg" alt="Purlins5 (1.2)" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Purlins5 (1.2) (Size: 2x6)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10079, 'Purlins5 (1.2)', 560, '2x6', 'uploads/steel-purlins-min.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/steel-purlins-min.jpg" alt="Purlins6 (1.5)" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Purlins6 (1.5) (Size: 2x6)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10080, 'Purlins6 (1.5)', 640, '2x6', 'uploads/steel-purlins-min.jpg')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/images (1).jfif" alt="Steel Matting1" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Steel Matting1 (Size: 6)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10081, 'Steel Matting1', 650, '6', 'uploads/images (1).jfif')">
                            Add
                        </button>
                                            </li>
                                    <li class="flex items-center bg-gray-50 p-4 rounded-lg shadow hover:bg-gray-100 transition duration-300">
                        <img src="uploads/images (1).jfif" alt="Steel Matting2" class="product-image rounded-lg mr-4">
                        <div class="flex-grow">
                            <p class="font-medium text-gray-800">Steel Matting2 (Size: 4)</p>
                        </div>
                        <button class="ml-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="openModal(10082, 'Steel Matting2', 420, '4', 'uploads/images (1).jfif')">
                            Add
                        </button>
                                            </li>
                            </ul>
        </div>
        
        <!-- Cart Summary Section -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Cart Summary</h2>

            <!-- Customer Name -->
            <div class="mb-4">
                <label for="customerName" class="block font-semibold text-gray-600 mb-1">Customer Name:</label>
                <input type="text" id="customerName" placeholder="Enter customer name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Date -->
            <div class="mb-4">
                <label for="transactionDate" class="block font-semibold text-gray-600 mb-1">Date:</label>
                <input type="date" id="transactionDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>


            <table class="w-full border border-gray-300">
                <thead>
                    <tr>
                        <th class="border px-2 py-2 bg-gray-200">ProductName</th>
                        <th class="border px-2 py-2 bg-gray-200">Size</th>
                        <th class="border px-2 py-2 bg-gray-200">Quantity</th>
                        <th class="border px-2 py-2 bg-gray-200">Actions</th>
                        <th class="border px-2 py-2 bg-gray-200"></th>
                    </tr>
                </thead>
                <tbody id="cartItems"></tbody>
            </table>
            <button class="mt-4 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="showReceipt()">Generate Receipt</button>
        </div>
    </div>

    <!-- Product Modal -->
    <div id="productModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center h-full">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4" id="modalProductName"></h2>
                <img id="modalProductImage" class="w-32 h-32 mb-4" alt="Product Image">
                <p id="modalProductSize" class="mb-2"></p>
                <label class="block mb-2">Quantity:</label>
                <input id="modalProductQuantity" type="number" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button class="mt-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300" id="addToCartButton">Add to Cart</button>
                <button class="mt-4 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center h-full">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-4">Receipt</h2>
                <p id="receiptCompanyName" class="font-semibold">GSL25 Construction Supplies</p>
                <p id="receiptCustomerName" class="mb-2"></p>
                <p id="receiptDate" class="mb-4"></p>
                <table class="w-full receipt-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Size</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody id="receiptItems"></tbody>
                </table>
                <button class="mt-4 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="printReceipt()">Print Receipt</button>
                <button class="mt-4 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition duration-300" onclick="closeReceipt()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript for managing the cart and receipts
    let cart = [];

function openModal(productId, productName, price, size, imageUrl) {
    document.getElementById('modalProductName').innerText = productName;
    document.getElementById('modalProductSize').innerText = `Size: ${size}`;
    document.getElementById('modalProductImage').src = imageUrl;
    document.getElementById('modalProductQuantity').value = 1; // Reset quantity
    document.getElementById('productModal').classList.remove('hidden');
    document.getElementById('addToCartButton').onclick = function() {
        addToCart(productId, productName, size, price);
        closeModal();
    };
}

function closeModal() {
    document.getElementById('productModal').classList.add('hidden');
}

function addToCart(productId, productName, size, price) {
    const quantity = parseInt(document.getElementById('modalProductQuantity').value);
    const existingProductIndex = cart.findIndex(item => item.id === productId);

    if (existingProductIndex > -1) {
        cart[existingProductIndex].quantity += quantity; // Update quantity if already in cart
    } else {
        cart.push({ id: productId, name: productName, size: size, quantity: quantity, price: price });
    }
    renderCart();
}

function renderCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    cartItemsContainer.innerHTML = ''; // Clear existing items

    cart.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="border px-2 py-2">${item.name}</td>
            <td class="border px-2 py-2">${item.size}</td>
            <td class="border px-2 py-2">${item.quantity}</td>
            <td class="border px-2 py-2">
                <button class="text-red-500" onclick="removeFromCart(${item.id})">Remove</button>
            </td>
        `;
        cartItemsContainer.appendChild(row);
    });
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId); // Remove item from cart
    renderCart();
}

function showReceipt() {
    const customerName = document.getElementById('customerName').value;
    const transactionDate = document.getElementById('transactionDate').value;
    const receiptItemsContainer = document.getElementById('receiptItems');
    const receiptCompanyName = 'GSL25 Construction Supplies'; // Company Name
    document.getElementById('receiptCompanyName').innerText = receiptCompanyName;
    document.getElementById('receiptCustomerName').innerText = customerName;
    document.getElementById('receiptDate').innerText = transactionDate;

    receiptItemsContainer.innerHTML = ''; // Clear existing items

    cart.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="border px-2 py-2">${item.name}</td>
            <td class="border px-2 py-2">${item.size}</td>
            <td class="border px-2 py-2">${item.quantity}</td>
        `;
        receiptItemsContainer.appendChild(row);
    });

    // Show the receipt modal
    document.getElementById('receiptModal').classList.remove('hidden');
}

function printReceipt() {
    const receiptContent = document.getElementById('receiptModal').innerHTML;
    const newWindow = window.open('', '', 'height=400,width=600');
    newWindow.document.write('<html><head><title>Receipt</title>');
    newWindow.document.write('</head><body >');
    newWindow.document.write(receiptContent);
    newWindow.document.write('</body></html>');
    newWindow.document.close();
    newWindow.print();
    closeReceipt();
}

function closeReceipt() {
    document.getElementById('receiptModal').classList.add('hidden');
}

</script>
</body>
</html>