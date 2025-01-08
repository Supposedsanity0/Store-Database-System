<?php
include 'db_connection.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product ID to delete
    $product_id = $_POST['product_id'];

    // Step 1: Delete related records in the Customer table
    $sql_delete_customer = "DELETE FROM Customer WHERE C_O_ID IN (SELECT Order_id FROM Orders WHERE O_P_ID = '$product_id')";
    if ($conn->query($sql_delete_customer) === TRUE) {
        echo "Related customer records deleted successfully!<br>";
    } else {
        echo "Error deleting customer records: " . $conn->error . "<br>";
    }

    // Step 2: Delete related records in the Orders table
    $sql_delete_orders = "DELETE FROM Orders WHERE O_P_ID = '$product_id'";
    if ($conn->query($sql_delete_orders) === TRUE) {
        echo "Related order records deleted successfully!<br>";
    } else {
        echo "Error deleting order records: " . $conn->error . "<br>";
    }

    // Step 3: Delete the product from the Products table
    $sql_delete_product = "DELETE FROM Products WHERE Product_ID = '$product_id'";
    if ($conn->query($sql_delete_product) === TRUE) {
        echo "Product deleted successfully!<br>";
    } else {
        echo "Error deleting product: " . $conn->error . "<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
</head>
<body>
    <h1>Delete Product</h1>
    <form method="post">
        Product ID: <input type="text" name="product_id" required><br>
        <input type="submit" value="Delete Product">
    </form>
    <br>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>