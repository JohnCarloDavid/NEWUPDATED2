<?php
// Include your database connection
include 'db_connection.php';

// Check if the product_id is passed via the URL
if (isset($_GET['product_id'])) {
    $product_id = htmlspecialchars($_GET['product_id']);

    // Delete product from the database
    $delete_query = "DELETE FROM tb_inventory WHERE product_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "Product deleted successfully!";
        header('Location: inventory.php'); // Redirect back to the inventory page after deletion
    } else {
        echo "Error deleting product.";
    }
} else {
    echo "Invalid product ID.";
}
?>
    