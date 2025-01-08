<?php
include 'db_connection.php'; // Include the database connection

// Function to validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $supplier_id = trim($_POST['supplier_id']);
    $supplier_name = trim($_POST['supplier_name']);
    $supplier_email = trim($_POST['supplier_email']);

    // Input validation
    $errors = [];

    // Validate Supplier ID
    if (empty($supplier_id)) {
        $errors[] = "Supplier ID is required.";
    }

    // Validate Supplier Name
    if (empty($supplier_name)) {
        $errors[] = "Supplier Name is required.";
    }

    // Validate Supplier Email
    if (empty($supplier_email)) {
        $errors[] = "Supplier Email is required.";
    } elseif (!validateEmail($supplier_email)) {
        $errors[] = "Invalid email format.";
    }

    // If there are no validation errors, proceed with database operations
    if (empty($errors)) {
        // Step 1: Validate that the Supplier_ID does not already exist
        $sql_check_supplier = "SELECT Supplier_ID FROM Supplier WHERE Supplier_ID = ?";
        $stmt = $conn->prepare($sql_check_supplier);
        $stmt->bind_param("s", $supplier_id);
        $stmt->execute();
        $result_supplier = $stmt->get_result();

        // If Supplier_ID does not exist, proceed with the insertion
        if ($result_supplier->num_rows == 0) {
            // Insert into database using prepared statements
            $sql = "INSERT INTO Supplier (Supplier_ID, Supplier_Name, Supplier_Email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $supplier_id, $supplier_name, $supplier_email);

            if ($stmt->execute()) {
                echo "New supplier added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            // Display error message if Supplier_ID already exists
            echo "Error: Supplier ID '$supplier_id' already exists.<br>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "Error: $error<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supplier</title>
</head>
<body>
    <h1>Add New Supplier</h1>
    <form method="post">
        Supplier ID: <input type="text" name="supplier_id" required><br>
        Supplier Name: <input type="text" name="supplier_name" required><br>
        Supplier Email: <input type="email" name="supplier_email" required><br>
        <input type="submit" value="Add Supplier">
    </form>
    <br>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>