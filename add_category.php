<?php
include 'db_connection.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];

    // Step 1: Validate that the Category_ID does not already exist
    $sql_check_category = "SELECT Category_ID FROM Category WHERE Category_ID = '$category_id'";
    $result_category = $conn->query($sql_check_category);

    // If Category_ID does not exist, proceed with the insertion
    if ($result_category->num_rows == 0) {
        // Insert into database
        $sql = "INSERT INTO Category (Category_ID, Category_Name)
                VALUES ('$category_id', '$category_name')";

        if ($conn->query($sql) === TRUE) {
            echo "New category added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Display error message if Category_ID already exists
        echo "Error: Category ID '$category_id' already exists.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
</head>
<body>
    <h1>Add New Category</h1>
    <form method="post">
        Category ID: <input type="text" name="category_id" required><br>
        Category Name: <input type="text" name="category_name" required><br>
        <input type="submit" value="Add Category">
    </form>
    <br>
    <a href="index.php">Return to Dashboard</a>
</body>
</html>

<?php
$conn->close(); // Close the connection
?>