<?php
session_start(); // Start the session
include 'db_connection.php'; // Include the database connection

$order_id = '';
$order_items = [];

// Step 1: Fetch order items if Order_id is provided
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['order_id'])) {
    $order_id = trim($_GET['order_id']); // Trim whitespace

    // Validate order_id
    if (!is_numeric($order_id)) {
        echo "<p>Invalid Order ID. Please enter a valid numeric Order ID.</p>";
    } else {
        // Fetch items in the selected order
        $sql = "SELECT oi.Order_Item_ID, oi.OI_P_ID, p.Product_Name, oi.Quantity, oi.Price 
                FROM order_items oi
                JOIN Products p ON oi.OI_P_ID = p.Product_ID
                WHERE oi.Oi_O_id = '$order_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $order_items[] = $row;
            }
        } else {
            echo "<p>No items found for Order ID: $order_id.</p>";
        }
    }
}

// Step 2: Handle form submission for returning items
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_items'])) {
    // Retrieve the order_id from the form
    $order_id = isset($_POST['order_id']) ? trim($_POST['order_id']) : '';

    // Validate order_id
    if (!is_numeric($order_id)) {
        echo "<p>Invalid Order ID. Please enter a valid numeric Order ID.</p>";
    } else {
        // Check if 'selected_items' key exists in $_POST
        $selected_items = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];
        $return_quantities = isset($_POST['return_quantity']) ? $_POST['return_quantity'] : [];

        // Insert a new record into the Returns table
        $return_date = date('Y-m-d'); // Current date
        $sql_insert_return = "INSERT INTO Returns (R_O_ID, Return_Date) VALUES ('$order_id', '$return_date')";
        
        if ($conn->query($sql_insert_return) === TRUE) {
            $return_id = $conn->insert_id; // Get the auto-generated Return_ID

            // If no items are selected, return the entire order
            if (empty($selected_items)) {
                // Fetch all items in the order
                $sql_fetch_all_items = "SELECT Order_Item_ID, OI_P_ID, Quantity, Price 
                                       FROM order_items 
                                       WHERE Oi_O_id = '$order_id'";
                $result_all_items = $conn->query($sql_fetch_all_items);

                if ($result_all_items->num_rows > 0) {
                    while ($item = $result_all_items->fetch_assoc()) {
                        $order_item_id = $item['Order_Item_ID'];
                        $product_id = $item['OI_P_ID'];
                        $quantity = $item['Quantity'];
                        $price = $item['Price'];

                        // Insert the returned item into the Return_Items table
                        $sql_insert_return_item = "INSERT INTO Return_Items (RI_R_ID, RI_P_ID, Quantity, Price) 
                                                  VALUES ('$return_id', '$product_id', '$quantity', '$price')";
                        if ($conn->query($sql_insert_return_item) !== TRUE) {
                            echo "Error inserting return item: " . $conn->error;
                        }

                        // Add the returned quantity back to the product
                        $sql_update_product = "UPDATE Products SET Quantity = Quantity + $quantity WHERE Product_ID = '$product_id'";
                        if ($conn->query($sql_update_product) !== TRUE) {
                            echo "Error updating product quantity for Product ID $product_id: " . $conn->error;
                        }

                        // Delete the returned item from the order_items table
                        $sql_delete_item = "DELETE FROM order_items WHERE Order_Item_ID = $order_item_id";
                        if ($conn->query($sql_delete_item) !== TRUE) {
                            echo "Error deleting item $order_item_id: " . $conn->error;
                        }
                    }
                    echo "<p>The entire order has been returned successfully, and quantities have been updated!</p>";
                }
            } else {
                // Return only the selected items
                foreach ($selected_items as $order_item_id) {
                    // Fetch the item details
                    $sql_fetch_item = "SELECT OI_P_ID, Quantity, Price FROM order_items WHERE Order_Item_ID = $order_item_id";
                    $result_item = $conn->query($sql_fetch_item);

                    if ($result_item->num_rows > 0) {
                        $item = $result_item->fetch_assoc();
                        $product_id = $item['OI_P_ID'];
                        $quantity = $item['Quantity'];
                        $price = $item['Price'];

                        // Get the return quantity for this item
                        $return_quantity = $return_quantities[$order_item_id];

                        // Validate the return quantity
                        if ($return_quantity > 0 && $return_quantity <= $quantity) {
                            // Insert the returned item into the Return_Items table
                            $sql_insert_return_item = "INSERT INTO Return_Items (RI_R_ID, RI_P_ID, Quantity, Price) 
                                                      VALUES ('$return_id', '$product_id', '$return_quantity', '$price')";
                            if ($conn->query($sql_insert_return_item) !== TRUE) {
                                echo "Error inserting return item: " . $conn->error;
                            }

                            // Add the returned quantity back to the product
                            $sql_update_product = "UPDATE Products SET Quantity = Quantity + $return_quantity WHERE Product_ID = '$product_id'";
                            if ($conn->query($sql_update_product) !== TRUE) {
                                echo "Error updating product quantity for Product ID $product_id: " . $conn->error;
                            }

                            // Update the order_items table to reflect the remaining quantity
                            $remaining_quantity = $quantity - $return_quantity;
                            if ($remaining_quantity > 0) {
                                $sql_update_order_item = "UPDATE order_items SET Quantity = $remaining_quantity WHERE Order_Item_ID = $order_item_id";
                            } else {
                                // If no items are left, delete the order item
                                $sql_update_order_item = "DELETE FROM order_items WHERE Order_Item_ID = $order_item_id";
                            }

                            if ($conn->query($sql_update_order_item) !== TRUE) {
                                echo "Error updating order item $order_item_id: " . $conn->error;
                            }
                        } else {
                            echo "<p>Invalid return quantity for Product ID $product_id.</p>";
                        }
                    }
                }
                echo "<p>Selected items have been returned successfully, and quantities have been updated!</p>";
            }
        } else {
            echo "Error creating return record: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Items</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #888;
        }
        .return-button {
            margin-top: 20px;
        }
        .quantity-input {
            width: 60px;
        }
    </style>
</head>
<body>
    <h1>Return Items from Order</h1>

    <!-- Step 1: Form to input Order_id -->
    <?php if (!isset($_GET['order_id'])): ?>
        <form method="get" action="">
            <label for="order_id">Enter Order ID:</label>
            <input type="text" name="order_id" required>
            <button type="submit">Fetch Order Items</button>
        </form>
    <?php endif; ?>

    <!-- Step 2: Display items in the selected order for returning -->
    <?php if (!empty($order_items)): ?>
        <form method="post" action="">
            <!-- Hidden input to retain the order_id -->
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">

            <table>
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Quantity Purchased</th>
                        <th>Return Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_items[]" value="<?php echo $item['Order_Item_ID']; ?>">
                            </td>
                            <td><?php echo htmlspecialchars($item['OI_P_ID']); ?></td>
                            <td><?php echo htmlspecialchars($item['Product_Name']); ?></td>
                            <td><?php echo htmlspecialchars($item['Quantity']); ?></td>
                            <td>
                                <input type="number" 
                                       name="return_quantity[<?php echo $item['Order_Item_ID']; ?>]" 
                                       class="quantity-input" 
                                       min="0" 
                                       max="<?php echo $item['Quantity']; ?>" 
                                       value="0">
                            </td>
                            <td><?php echo htmlspecialchars($item['Price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="return-button">
                <button type="submit" name="return_items">Return Selected Items</button>
            </div>
        </form>
    <?php endif; ?>

    <br>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>