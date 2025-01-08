<?php
include 'db_connection.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $supplier_id = $_POST['supplier_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $barcode = $_POST['barcode']; // Get barcode from the form

    // Step 1: Validate that the Category_ID exists
    $sql_check_category = "SELECT Category_ID FROM Category WHERE Category_ID = '$category_id'";
    $result_category = $conn->query($sql_check_category);

    // Step 2: Validate that the Supplier_ID exists
    $sql_check_supplier = "SELECT Supplier_ID FROM Supplier WHERE Supplier_ID = '$supplier_id'";
    $result_supplier = $conn->query($sql_check_supplier);

    // If both Category_ID and Supplier_ID exist, proceed with the insertion
    if ($result_category->num_rows > 0 && $result_supplier->num_rows > 0) {
        // Insert into database
        $sql = "INSERT INTO Products (Product_ID, Product_Name, P_C_ID, P_S_ID, Quantity, Price, Barcode)
                VALUES ('$product_id', '$product_name', '$category_id', '$supplier_id', $quantity, $price, '$barcode')";

        if ($conn->query($sql) === TRUE) {
            echo "New product added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Add Product</title>
</head>
<body>
    <h1>Add New Product</h1>
    <form method="post">
        Product ID: <input type="text" name="product_id" required><br>
        Product Name: <input type="text" name="product_name" required><br>
        Category ID: <input type="text" name="category_id" required><br>
        Supplier ID: <input type="text" name="supplier_id" required><br>
        Quantity: <input type="number" name="quantity" required><br>
        Price: <input type="number" name="price" required><br>
        Barcode: <input type="text" name="barcode" required><br> <!-- New field for barcode -->
        <input type="submit" value="Add Product">
    </form>
    <br>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>