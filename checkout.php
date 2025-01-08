<?php
session_start(); // Start the session
include 'db_connection.php'; // Include the database connection

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to the cart page if the cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Get the current date for Order_Date
$order_date = date('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS

// Calculate the total amount
$cart = $_SESSION['cart'];
$total_amount = 0;

// Check stock availability and calculate the total amount
foreach ($cart as $product_id => $quantity) {
    // Fetch product details
    $sql = "SELECT Price, Quantity FROM Products WHERE Product_ID = '$product_id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_amount += $row['Price'] * $quantity;

        // Check if there is enough stock
        if ($row['Quantity'] < $quantity) {
            echo "<script>alert('Not enough stock for product with ID $product_id.'); window.location.href = 'cart.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Product with ID $product_id not found.'); window.location.href = 'cart.php';</script>";
        exit();
    }
}

// Step 1: Validate customer and employee IDs
$customer_id = $_POST['customer_id'] ?? null; // Use NULL if no customer is selected
$employee_id = $_POST['employee_id'] ?? 0; // Default to "Default" if no employee is selected

// Ensure the customer ID is valid
if ($customer_id !== null) {
    $sql = "SELECT Customer_ID FROM Customer WHERE Customer_ID = '$customer_id'";
    $result = $conn->query($sql);
    if (!$result || $result->num_rows === 0) {
        echo "<script>alert('Invalid customer selected.'); window.location.href = 'cart.php';</script>";
        exit();
    }
}

// Step 2: Insert the order into the Orders table
$sql = "INSERT INTO Orders (Order_Date, Total_Amount, O_C_ID, O_E_ID) 
        VALUES ('$order_date', $total_amount, " . ($customer_id !== null ? "'$customer_id'" : "NULL") . ", $employee_id)";
if ($conn->query($sql) === TRUE) {
    $order_id = $conn->insert_id; // Get the last inserted order ID (auto-generated)

    // Step 3: Insert order items into the order_items table and reduce product quantity
    foreach ($cart as $product_id => $quantity) {
        // Fetch the product price and current quantity
        $sql = "SELECT Price, Quantity FROM Products WHERE Product_ID = '$product_id'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $price = $row['Price'];
            $current_quantity = $row['Quantity'];

            // Insert into the order_items table
            $sql = "INSERT INTO order_items (Oi_O_id, OI_P_ID, Quantity, Price) 
                    VALUES ($order_id, '$product_id', $quantity, $price)";
            if (!$conn->query($sql)) {
                echo "<script>alert('Error inserting order item: " . $conn->error . "'); window.location.href = 'cart.php';</script>";
                exit();
            }

            // Reduce the product quantity in the Products table
            $new_quantity = $current_quantity - $quantity;
            $sql = "UPDATE Products SET Quantity = $new_quantity WHERE Product_ID = '$product_id'";
            if (!$conn->query($sql)) {
                echo "<script>alert('Error updating product quantity: " . $conn->error . "'); window.location.href = 'cart.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Product with ID $product_id not found.'); window.location.href = 'cart.php';</script>";
            exit();
        }
    }

    // Clear the cart
    unset($_SESSION['cart']);

    // Redirect to a success page or display a success message
    echo "<script>alert('Order placed successfully!'); window.location.href = 'orders.php';</script>";
    exit();
} else {
    echo "<script>alert('Error placing order: " . $conn->error . "'); window.location.href = 'cart.php';</script>";
    exit();
}

$conn->close(); // Close the connection
?>