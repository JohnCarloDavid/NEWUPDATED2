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

// Get POST data
$product_id = isset($_POST['product_id']) ? trim($_POST['product_id']) : '';
$action = isset($_POST['action']) ? trim($_POST['action']) : '';

// Validate input
if (empty($product_id) || empty($action)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

// Define action queries
switch ($action) {
    case 'increase':
        $sql = "UPDATE tb_inventory SET quantity = quantity + 1 WHERE product_id = ?";
        break;
    case 'decrease':
        $sql = "UPDATE tb_inventory SET quantity = quantity - 1 WHERE product_id = ?";
        break;
    case 'delete':
        $sql = "DELETE FROM tb_inventory WHERE product_id = ?";
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        exit;
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $product_id);
$result = $stmt->execute();

// Send response
if ($result) {
    echo json_encode(['success' => true, 'message' => 'Operation successful.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Operation failed.']);
}
?>
