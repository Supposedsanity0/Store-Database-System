<?php
session_start(); // Start the session
include 'db_connection.php'; // Include the database connection

// Handle clearing the cart
if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']); // Clear the cart
    header("Location: cart.php"); // Refresh the page
    exit();
}

// Handle deleting an individual item from the cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove']; // Get the product ID to remove
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]); // Remove the item from the cart
    }
    header("Location: cart.php"); // Refresh the page
    exit();
}

// Fetch cart items
$cart = $_SESSION['cart'] ?? [];
$products = [];
$total_amount = 0;

if (!empty($cart)) {
    // Fetch product details for items in the cart
    $product_ids = implode("','", array_keys($cart)); // Add quotes around each product ID
    $sql = "SELECT * FROM Products WHERE Product_ID IN ('$product_ids')";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
            $total_amount += $row['Price'] * $cart[$row['Product_ID']];
        }
    } else {
        echo "Error fetching products: " . $conn->error;
    }
}

// Fetch all customers for dropdown
$sql_customers = "SELECT * FROM Customer";
$result_customers = $conn->query($sql_customers);

// Fetch employees with sales-related roles for dropdown
$sql_employees = "SELECT * FROM Employee 
                  WHERE Role IN ('Manager', 'Sales Associate', 'Senior Sales', 'Sales Supervisor')";
$result_employees = $conn->query($sql_employees);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        select, input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h1>Cart</h1>
    <?php if (empty($cart)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['Product_Name']; ?></td>
                    <td><?php echo $product['Price']; ?></td>
                    <td><?php echo $cart[$product['Product_ID']]; ?></td>
                    <td><?php echo $product['Price'] * $cart[$product['Product_ID']]; ?></td>
                    <td class="actions">
                        <a href="cart.php?remove=<?php echo $product['Product_ID']; ?>" onclick="return confirm('Are you sure you want to remove this item?');">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" align="right"><strong>Total Amount:</strong></td>
                <td><?php echo $total_amount; ?></td>
            </tr>
        </table>
        <br>
        <form method="post" action="checkout.php">
            <div class="form-group">
                <label for="customer_id">Select Customer:</label>
                <select name="customer_id" id="customer_id">
                    <option value="">-- Select Customer --</option>
                    <?php while ($customer = $result_customers->fetch_assoc()): ?>
                        <option value="<?php echo $customer['Customer_ID']; ?>">
                            <?php echo $customer['Customer_Name'] . " (" . $customer['Phone_Number'] . ")"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="employee_id">Select Sales Representative:</label>
                <select name="employee_id" id="employee_id">
                    <option value="0">Default</option> <!-- Default "Default" option -->
                    <?php while ($employee = $result_employees->fetch_assoc()): ?>
                        <option value="<?php echo $employee['Employee_ID']; ?>">
                            <?php echo $employee['Employee_name'] . " (" . $employee['Role'] . ")"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="place_order">Place Order</button>
        </form>
        <br>
        <form method="post" action="cart.php">
            <button type="submit" name="clear_cart" onclick="return confirm('Are you sure you want to clear the cart?');">Clear Cart</button>
        </form>
    <?php endif; ?>
    <br>
    <a href="view_products.php">Continue Shopping</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>