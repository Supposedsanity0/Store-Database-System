<?php
session_start(); // Start the session
include 'db_connection.php'; // Include the database connection

// Handle adding a new employee
if (isset($_POST['add_employee'])) {
    $employee_name = $_POST['employee_name'];
    $role = $_POST['role'];
    $join_date = $_POST['join_date'];
    $email = $_POST['email'];

    // Insert the new employee into the database
    $sql = "INSERT INTO Employee (Employee_name, Role, Join_date, Email) 
            VALUES ('$employee_name', '$role', '$join_date', '$email')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Employee added successfully!');</script>";
        header("Location: view_employees.php"); // Redirect to the view employees page
        exit();
    } else {
        echo "<script>alert('Error adding employee: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
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
    <h1>Add New Employee</h1>
    <form method="post" action="add_employee.php">
        <div class="form-group">
            <label for="employee_name">Employee Name:</label>
            <input type="text" id="employee_name" name="employee_name" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <input type="text" id="role" name="role" required>
        </div>
        <div class="form-group">
            <label for="join_date">Join Date:</label>
            <input type="date" id="join_date" name="join_date" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
        </div>
        <button type="submit" name="add_employee">Add Employee</button>
    </form>
    <br>
    <a href="view_employees.php">View Employees</a>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>