<?php
include 'db_connection.php'; // Include the database connection

$product_id = '';
$product_data = null;

// Step 1: Fetch product data if Product_ID is provided
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product data from the database
    $sql = "SELECT * FROM Products WHERE Product_ID = '$product_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product_data = $result->fetch_assoc();
    } else {
        echo "Error: Product with ID '$product_id' not found.<br>";
    }
}

// Step 2: Handle form submission to update the product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $supplier_id = $_POST['supplier_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Step 1: Validate that the Category_ID exists
    $sql_check_category = "SELECT Category_ID FROM Category WHERE Category_ID = '$category_id'";
    $result_category = $conn->query($sql_check_category);

    // Step 2: Validate that the Supplier_ID exists
    $sql_check_supplier = "SELECT Supplier_ID FROM Supplier WHERE Supplier_ID = '$supplier_id'";
    $result_supplier = $conn->query($sql_check_supplier);

    // If both Category_ID and Supplier_ID exist, proceed with the update
    if ($result_category->num_rows > 0 && $result_supplier->num_rows > 0) {
        // Update product in the database
        $sql = "UPDATE Products SET 
                Product_Name='$product_name', 
                P_C_ID='$category_id', 
                P_S_ID='$supplier_id', 
                Quantity=$quantity, 
                Price=$price 
                WHERE Product_ID='$product_id'";

        if ($conn->query($sql) === TRUE) {
            echo "Product updated successfully!";
        } else {
            echo "Error updating product: " . $conn->error;
        }
    } else {
        // Display error message if Category_ID or Supplier_ID does not exist
        if ($result_category->num_rows == 0) {
            echo "Error: Category ID '$category_id' does not exist.<br>";
        }
        if ($result_supplier->num_rows == 0) {
            echo "Error: Supplier ID '$supplier_id' does not exist.<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
</head>
<body>
    <h1>Update Product</h1>

    <!-- Step 1: Form to input Product_ID -->
    <?php if (!isset($_GET['product_id'])): ?>
        <form method="get" action="">
            <label for="product_id">Enter Product ID:</label>
            <input type="text" name="product_id" required>
            <input type="submit" value="Fetch Product">
        </form>
    <?php endif; ?>

    <!-- Step 2: Display form with existing data for editing -->
    <?php if ($product_data): ?>
        <form method="post">
            <input type="hidden" name="product_id" value="<?php echo $product_data['Product_ID']; ?>">
            Product Name: <input type="text" name="product_name" value="<?php echo $product_data['Product_Name']; ?>" required><br>
            Category ID: <input type="text" name="category_id" value="<?php echo $product_data['P_C_ID']; ?>" required><br>
            Supplier ID: <input type="text" name="supplier_id" value="<?php echo $product_data['P_S_ID']; ?>" required><br>
            Quantity: <input type="number" name="quantity" value="<?php echo $product_data['Quantity']; ?>" required><br>
            Price: <input type="number" name="price" value="<?php echo $product_data['Price']; ?>" required><br>
            <input type="submit" value="Update Product">
        </form>
    <?php endif; ?>

    <br>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>