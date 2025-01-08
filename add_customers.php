<?php
session_start(); // Start the session
include 'db_connection.php'; // Include the database connection

// Handle adding a new customer
if (isset($_POST['add_customers'])) {
    $customer_name = $_POST['customer_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    // Check if the phone number already exists
    $sql_check_phone = "SELECT Customer_ID FROM Customer WHERE Phone_Number = '$phone_number'";
    $result_check_phone = $conn->query($sql_check_phone);

    if ($result_check_phone && $result_check_phone->num_rows > 0) {
        // Phone number already exists
        echo "<script>alert('Error: The phone number $phone_number is already in use.');</script>";
    } else {
        // Insert the new customer into the database
        $sql = "INSERT INTO Customer (Customer_Name, Phone_Number, Email) 
                VALUES ('$customer_name', '$phone_number', '$email')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Customer added successfully!');</script>";
            header("Location: view_customers.php"); // Redirect to the manage customers page
            exit();
        } else {
            echo "<script>alert('Error adding customer: " . $conn->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h1>Add New Customer</h1>
    <form method="post" action="add_customers.php">
        <div class="form-group">
            <label for="customer_name">Customer Name:</label>
            <input type="text" id="customer_name" name="customer_name" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
        </div>
        <button type="submit" name="add_customers">Add Customer</button>
    </form>
    <br>
    <a href="view_customers.php">Back to view Customers</a>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>