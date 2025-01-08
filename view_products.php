<?php
session_start(); // Start the session to manage the cart
include 'db_connection.php'; // Include the database connection

// Initialize the search query variable
$searchQuery = '';

// Check if a search query is submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    // Prepare the SQL query to search by Barcode, Product_ID, or Product_Name
    $sql = "SELECT Product_ID, Product_Name, P_C_ID, P_S_ID, Quantity, Price, Barcode 
            FROM Products 
            WHERE Barcode LIKE '%$searchQuery%' 
               OR Product_ID LIKE '%$searchQuery%' 
               OR Product_Name LIKE '%$searchQuery%'";
} else {
    // Fetch all products if no search query is provided
    $sql = "SELECT Product_ID, Product_Name, P_C_ID, P_S_ID, Quantity, Price, Barcode FROM Products";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .actions {
            white-space: nowrap;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input[type="text"] {
            width: 300px;
            padding: 8px;
            font-size: 16px;
        }
        .search-bar button {
            padding: 8px 16px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>Products List</h1>
    
    <!-- Search Bar -->
    <div class="search-bar">
        <form method="get" action="">
            <input type="text" name="search" placeholder="Search by Barcode, Product ID, or Name" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Category ID</th>
            <th>Supplier ID</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Barcode</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Check if 'Barcode' key exists and is not empty
                $barcode = isset($row['Barcode']) && !empty($row['Barcode']) ? $row['Barcode'] : 'N/A';
                
                echo "<tr>
                        <td>{$row['Product_ID']}</td>
                        <td>{$row['Product_Name']}</td>
                        <td>{$row['P_C_ID']}</td>
                        <td>{$row['P_S_ID']}</td>
                        <td>{$row['Quantity']}</td>
                        <td>{$row['Price']}</td>
                        <td>{$barcode}</td> <!-- Display Barcode -->
                        <td class='actions'>
                            <form method='post' action='add_to_cart.php' style='display:inline;'>
                                <input type='hidden' name='product_id' value='{$row['Product_ID']}'>
                                <input type='number' name='quantity' value='1' min='1' max='{$row['Quantity']}' style='width: 60px;'>
                                <button type='submit'>Add to Cart</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No products found</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="cart.php">View Cart</a> | <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>