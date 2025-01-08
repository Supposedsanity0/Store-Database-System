<?php
session_start(); // Start the session
include 'db_connection.php'; // Include the database connection

// Handle updating a customer
if (isset($_POST['update_customer'])) {
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    // Update the customer in the database
    $sql = "UPDATE Customer 
            SET Customer_Name = '$customer_name', 
                Phone_Number = '$phone_number', 
                Email = '$email' 
            WHERE Customer_ID = '$customer_id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Customer updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating customer: " . $conn->error . "');</script>";
    }
    header("Location: view_customers.php"); // Refresh the page
    exit();
}

// Fetch all customers
$sql_customers = "SELECT * FROM Customer";
$result_customers = $conn->query($sql_customers);

if (!$result_customers) {
    die("Error fetching customers: " . $conn->error); // Stop execution and display the error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
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
        input, select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .edit-form {
            display: none;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Manage Customers</h1>

    <!-- Add Customer Button -->
    <div>
        <a href="add_customers.php">
            <button>Add New Customer</button>
        </a>
    </div>

    <!-- Display Customers Table -->
    <table>
        <tr>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php if ($result_customers->num_rows > 0): ?>
            <?php while ($customer = $result_customers->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $customer['Customer_ID']; ?></td>
                    <td><?php echo $customer['Customer_Name']; ?></td>
                    <td><?php echo $customer['Phone_Number']; ?></td>
                    <td><?php echo $customer['Email']; ?></td>
                    <td class="actions">
                        <button onclick="openEditForm(
                            '<?php echo $customer['Customer_ID']; ?>',
                            '<?php echo $customer['Customer_Name']; ?>',
                            '<?php echo $customer['Phone_Number']; ?>',
                            '<?php echo $customer['Email']; ?>'
                        )">Edit</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No customers found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Edit Customer Form -->
    <div id="editForm" class="edit-form">
        <h2>Edit Customer</h2>
        <form method="post" action="view_customers.php">
            <input type="hidden" id="edit_customer_id" name="customer_id">
            <div class="form-group">
                <label for="edit_customer_name">Customer Name:</label>
                <input type="text" id="edit_customer_name" name="customer_name" required>
            </div>
            <div class="form-group">
                <label for="edit_phone_number">Phone Number:</label>
                <input type="text" id="edit_phone_number" name="phone_number">
            </div>
            <div class="form-group">
                <label for="edit_email">Email:</label>
                <input type="email" id="edit_email" name="email">
            </div>
            <button type="submit" name="update_customer">Update Customer</button>
            <button type="button" onclick="closeEditForm()">Cancel</button>
        </form>
    </div>

    <script>
        // Function to open the edit form with customer details
        function openEditForm(customerId, customerName, phoneNumber, email) {
            document.getElementById('edit_customer_id').value = customerId;
            document.getElementById('edit_customer_name').value = customerName;
            document.getElementById('edit_phone_number').value = phoneNumber;
            document.getElementById('edit_email').value = email;
            document.getElementById('editForm').style.display = 'block';
        }

        // Function to close the edit form
        function closeEditForm() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>