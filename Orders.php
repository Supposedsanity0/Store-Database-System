<?php
session_start(); // Start the session
require 'vendor/autoload.php'; // Include Composer autoloader
include 'db_connection.php'; // Include the database connection

// Handle Excel file download
if (isset($_GET['download_excel'])) {
    // Create a new spreadsheet
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $sheet->setCellValue('A1', 'Order ID');
    $sheet->setCellValue('B1', 'Order Date');
    $sheet->setCellValue('C1', 'Customer Name');
    $sheet->setCellValue('D1', 'Employee Name');
    $sheet->setCellValue('E1', 'Total Amount');
    $sheet->setCellValue('F1', 'Returned Amount');
    $sheet->setCellValue('G1', 'Net Total');

    // Fetch all orders with customer and employee details
    $sql_orders = "SELECT o.Order_id, o.Order_Date, o.Total_Amount, 
                          c.Customer_Name, e.Employee_name 
                   FROM Orders o
                   JOIN Customer c ON o.O_C_ID = c.Customer_ID
                   JOIN Employee e ON o.O_E_ID = e.Employee_ID
                   ORDER BY o.Order_Date DESC";
    $result_orders = $conn->query($sql_orders);

    $row = 2; // Start from the second row
    $total_orders = 0;
    $total_returns = 0;

    if ($result_orders->num_rows > 0) {
        while ($order = $result_orders->fetch_assoc()) {
            $order_id = $order['Order_id'];
            $order_date = $order['Order_Date'];
            $customer_name = $order['Customer_Name'];
            $employee_name = $order['Employee_name'];
            $total_amount = $order['Total_Amount'];

            // Fetch returned items for this order from the Return_Items table
            $sql_returns = "SELECT SUM(ri.Quantity * ri.Price) AS Returned_Amount 
                            FROM Return_Items ri
                            JOIN Returns r ON ri.RI_R_ID = r.Return_ID
                            WHERE r.R_O_ID = '$order_id'";
            $result_returns = $conn->query($sql_returns);
            $returned_amount = $result_returns->fetch_assoc()['Returned_Amount'] ?? 0;

            // Calculate net total
            $net_total = $total_amount - $returned_amount;

            // Add data to the spreadsheet
            $sheet->setCellValue('A' . $row, $order_id);
            $sheet->setCellValue('B' . $row, $order_date);
            $sheet->setCellValue('C' . $row, $customer_name);
            $sheet->setCellValue('D' . $row, $employee_name);
            $sheet->setCellValue('E' . $row, $total_amount);
            $sheet->setCellValue('F' . $row, $returned_amount);
            $sheet->setCellValue('G' . $row, $net_total);

            // Update totals
            $total_orders += $total_amount;
            $total_returns += $returned_amount;

            $row++;
        }
    }

    // Add totals row
    $sheet->setCellValue('A' . $row, 'Total');
    $sheet->setCellValue('E' . $row, $total_orders);
    $sheet->setCellValue('F' . $row, $total_returns);
    $sheet->setCellValue('G' . $row, $total_orders - $total_returns);

    // Set headers for file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="orders_summary.xlsx"');
    header('Cache-Control: max-age=0');

    // Save the spreadsheet to a file
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

// Fetch all orders with customer and employee details
$sql_orders = "SELECT o.Order_id, o.Order_Date, o.Total_Amount, 
                      c.Customer_Name, e.Employee_name 
               FROM Orders o
               JOIN Customer c ON o.O_C_ID = c.Customer_ID
               JOIN Employee e ON o.O_E_ID = e.Employee_ID
               ORDER BY o.Order_Date DESC";
$result_orders = $conn->query($sql_orders);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        .order-details {
            margin-left: 20px;
        }
        .download-button {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Complete Orders</h1>
    <div class="download-button">
        <a href="?download_excel=1">
            <button>Download Orders Summary (Excel)</button>
        </a>
    </div>

    <?php
    if ($result_orders->num_rows > 0) {
        while ($order = $result_orders->fetch_assoc()) {
            $order_id = $order['Order_id'];
            $order_date = $order['Order_Date'];
            $customer_name = $order['Customer_Name'];
            $employee_name = $order['Employee_name'];
            $total_amount = $order['Total_Amount'];

            // Fetch order items for the current order
            $sql_items = "SELECT order_items.*, Products.Product_Name 
                          FROM order_items 
                          JOIN Products ON order_items.OI_P_ID = Products.Product_ID 
                          WHERE order_items.Oi_O_id = '$order_id'";
            $result_items = $conn->query($sql_items);

            echo "<div>
                    <h2>Order ID: $order_id</h2>
                    <p><strong>Order Date:</strong> $order_date</p>
                    <p><strong>Customer Name:</strong> $customer_name</p>
                    <p><strong>Employee Name:</strong> $employee_name</p>
                    <p><strong>Total Amount:</strong> $total_amount</p>
                    <h3>Order Items:</h3>
                    <table>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>";

            if ($result_items->num_rows > 0) {
                while ($item = $result_items->fetch_assoc()) {
                    $product_name = $item['Product_Name'];
                    $quantity = $item['Quantity'];
                    $price = $item['Price'];
                    $subtotal = $quantity * $price;

                    echo "<tr>
                            <td>$product_name</td>
                            <td>$quantity</td>
                            <td>$price</td>
                            <td>$subtotal</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No items found for this order.</td></tr>";
            }

            echo "</table>
                  </div><hr>";
        }
    } else {
        echo "<p>No orders found.</p>";
    }
    ?>
    <br>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>