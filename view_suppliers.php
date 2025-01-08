<?php
include 'db_connection.php'; // Include the database connection

// Fetch suppliers from the database
$sql = "SELECT * FROM Supplier";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching suppliers: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Suppliers</title>
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
    </style>
</head>
<body>
    <h1>Suppliers List</h1>
    <table>
        <tr>
            <th>Supplier ID</th>
            <th>Supplier Name</th>
            <th>Supplier Email</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Debugging: Print the entire row to check for the Supplier_Email key
                // echo "<pre>"; print_r($row); echo "</pre>";

                // Check if 'Supplier_Email' key exists and is not empty
                $supplier_email = isset($row['Supplier_Email']) && !empty($row['Supplier_Email']) ? $row['Supplier_Email'] : 'N/A';
                
                echo "<tr>
                        <td>{$row['Supplier_ID']}</td>
                        <td>{$row['Supplier_Name']}</td>
                        <td>{$supplier_email}</td>
                        <td class='actions'>
                            <a href='update_suppliers.php?id={$row['Supplier_ID']}'>Edit</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No suppliers found</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>