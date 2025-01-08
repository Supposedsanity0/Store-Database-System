<?php
include 'db_connection.php'; // Include the database connection

// Fetch employee details for editing
if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];
    $sql = "SELECT * FROM Employee WHERE Employee_ID = $employee_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Employee not found.");
    }
} else {
    die("Invalid request.");
}

// Handle updating an employee
if (isset($_POST['update_employee'])) {
    $employee_name = $_POST['employee_name'];
    $role = $_POST['role'];
    $join_date = $_POST['join_date'];
    $email = $_POST['email'];

    $sql = "UPDATE Employee 
            SET Employee_name = '$employee_name', Role = '$role', Join_date = '$join_date', Email = '$email'
            WHERE Employee_ID = $employee_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Employee updated successfully!');</script>";
        header("Location: view_employees.php"); // Redirect to the view employees page
        exit();
    } else {
        echo "<script>alert('Error updating employee: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employee</title>
    <style>
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
    </style>
</head>
<body>
    <h1>Update Employee</h1>
    <form method="post" action="update_employee.php?id=<?php echo $employee_id; ?>">
        <div class="form-group">
            <label for="employee_name">Employee Name:</label>
            <input type="text" id="employee_name" name="employee_name" value="<?php echo $row['Employee_name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <input type="text" id="role" name="role" value="<?php echo $row['Role']; ?>" required>
        </div>
        <div class="form-group">
            <label for="join_date">Join Date:</label>
            <input type="date" id="join_date" name="join_date" value="<?php echo $row['Join_date']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $row['Email']; ?>">
        </div>
        <button type="submit" name="update_employee">Update Employee</button>
    </form>
    <br>
    <a href="view_employees.php">Back to Employees List</a>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>