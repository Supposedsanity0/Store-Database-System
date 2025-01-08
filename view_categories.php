<?php
include 'db_connection.php'; // Include the database connection

// Handle updating a category
if (isset($_POST['update_category'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];

    // Update the category in the database
    $sql = "UPDATE Category SET Category_Name = '$category_name' WHERE Category_ID = '$category_id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Category updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating category: " . $conn->error . "');</script>";
    }
}

// Fetch all categories from the database
$sql = "SELECT Category_ID, Category_Name FROM Category";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Categories</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #888;
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
    <h1>View Categories</h1>

    <!-- Display the categories in a table -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Category_ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Category_Name']); ?></td>
                        <td>
                            <button onclick="openEditForm(
                                '<?php echo $row['Category_ID']; ?>',
                                '<?php echo htmlspecialchars($row['Category_Name']); ?>'
                            )">Edit</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-data">No categories found in the database.</p>
    <?php endif; ?>

    <!-- Edit Category Form -->
    <div id="editForm" class="edit-form">
        <h2>Edit Category</h2>
        <form method="post" action="view_categories.php">
            <input type="hidden" id="edit_category_id" name="category_id">
            <div class="form-group">
                <label for="edit_category_name">Category Name:</label>
                <input type="text" id="edit_category_name" name="category_name" required>
            </div>
            <button type="submit" name="update_category">Update Category</button>
            <button type="button" onclick="closeEditForm()">Cancel</button>
        </form>
    </div>

    <br>
    <a href="index.php">Return to Dashboard</a>

    <script>
        // Function to open the edit form with category details
        function openEditForm(categoryId, categoryName) {
            document.getElementById('edit_category_id').value = categoryId;
            document.getElementById('edit_category_name').value = categoryName;
            document.getElementById('editForm').style.display = 'block';
        }

        // Function to close the edit form
        function closeEditForm() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>