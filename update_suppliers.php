<?php
include 'db_connection.php'; // Include the database connection

// Initialize $supplier to avoid undefined variable warnings
$supplier = [];

// Fetch supplier details based on the ID from the query parameter
if (isset($_GET['id'])) {
    $supplier_id = $_GET['id'];
    $sql = "SELECT * FROM Supplier WHERE Supplier_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $supplier_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $supplier = $result->fetch_assoc();
    } else {
        echo "Error: Supplier not found.";
        exit(); // Stop execution if the supplier is not found
    }
} else {
    echo "Error: Supplier ID not provided.";
    exit(); // Stop execution if no Supplier ID is provided
}

// Handle form submission for updating supplier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_id = $_POST['supplier_id'];
    $supplier_name = trim($_POST['supplier_name']);
    $supplier_email = trim($_POST['supplier_email']);

    // Input validation
    $errors = [];
    if (empty($supplier_name)) {
        $errors[] = "Supplier Name is required.";
    }
    if (empty($supplier_email)) {
        $errors[] = "Supplier Email is required.";
    } elseif (!filter_var($supplier_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // If no errors, update the supplier
    if (empty($errors)) {
        $sql = "UPDATE Supplier SET Supplier_Name = ?, Supplier_Email = ? WHERE Supplier_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $supplier_name, $supplier_email, $supplier_id);

        if ($stmt->execute()) {
            echo "Supplier updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
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
    <title>Edit Supplier</title>
</head>
<body>
    <h1>Edit Supplier</h1>
    <form method="post">
        <input type="hidden" name="supplier_id" value="<?php echo htmlspecialchars($supplier['Supplier_ID']); ?>">
        Supplier Name: <input type="text" name="supplier_name" value="<?php echo htmlspecialchars($supplier['Supplier_Name'] ?? ''); ?>" required><br>
        Supplier Email: <input type="email" name="supplier_email" value="<?php echo htmlspecialchars($supplier['Supplier_Email'] ?? ''); ?>" required><br>
        <input type="submit" value="Update Supplier">
    </form>
    <br>
    <a href="view_suppliers.php">Back to Suppliers List</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>