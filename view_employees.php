<?php
include 'db_connection.php'; // Include the database connection

// Handle deleting an employee
if (isset($_GET['delete'])) {
    $employee_id = $_GET['delete'];
    $sql = "DELETE FROM Employee WHERE Employee_ID = $employee_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Employee deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting employee: " . $conn->error . "');</script>";
    }
}

// Fetch all employees from the database
$sql = "SELECT * FROM Employee";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees</title>
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
    <h1>Employees List</h1>
    <table>
        <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Role</th>
            <th>Join Date</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['Employee_ID']}</td>
                        <td>{$row['Employee_name']}</td>
                        <td>{$row['Role']}</td>
                        <td>{$row['Join_date']}</td>
                        <td>{$row['Email']}</td>
                        <td class='actions'>
                            <a href='update_employee.php?id={$row['Employee_ID']}'>Edit</a> |
                            <a href='view_employees.php?delete={$row['Employee_ID']}' onclick='return confirm(\"Are you sure you want to delete this employee?\");'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No employees found</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="add_employee.php">Add New Employee</a>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>