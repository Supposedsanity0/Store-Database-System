<?php
session_start(); // Start the session
include 'db_connection.php'; // Include the database connection

// Fetch all returns with their items
$sql_returns = "SELECT r.Return_ID, r.R_O_ID AS Order_ID, r.Return_Date, 
                       ri.RI_P_ID AS Product_ID, ri.Quantity, ri.Price, 
                       p.Product_Name
                FROM Returns r
                JOIN Return_Items ri ON r.Return_ID = ri.RI_R_ID
                JOIN Products p ON ri.RI_P_ID = p.Product_ID
                ORDER BY r.Return_Date DESC";
$result_returns = $conn->query($sql_returns);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Returns</title>
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
        .return-details {
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <h1>Returned Items</h1>

    <?php
    if ($result_returns->num_rows > 0) {
        $current_return_id = null;

        while ($row = $result_returns->fetch_assoc()) {
            $return_id = $row['Return_ID'];
            $order_id = $row['Order_ID'];
            $return_date = $row['Return_Date'];
            $product_id = $row['Product_ID'];
            $product_name = $row['Product_Name'];
            $quantity = $row['Quantity'];
            $price = $row['Price'];
            $subtotal = $quantity * $price;

            // Display return header if it's a new return
            if ($return_id !== $current_return_id) {
                if ($current_return_id !== null) {
                    echo "</table></div><hr>";
                }

                echo "<div class='return-details'>
                        <h2>Return ID: $return_id</h2>
                        <p><strong>Order ID:</strong> $order_id</p>
                        <p><strong>Return Date:</strong> $return_date</p>
                        <h3>Returned Items:</h3>
                        <table>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>";

                $current_return_id = $return_id;
            }

            // Display returned item
            echo "<tr>
                    <td>$product_id</td>
                    <td>$product_name</td>
                    <td>$quantity</td>
                    <td>$price</td>
                    <td>$subtotal</td>
                  </tr>";
        }

        // Close the last return table
        if ($current_return_id !== null) {
            echo "</table></div>";
        }
    } else {
        echo "<p>No returned items found.</p>";
    }
    ?>

    <br>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>