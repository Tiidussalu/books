<!-- Form to add a new author -->
<h3>Add New Author</h3>
<form action="./add_new_author.php" method="post">
    <!-- Input field for the author's first name -->
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" required>

    <!-- Input field for the author's last name -->
    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" required>

    <!-- Submit button to add the new author -->
    <button type="submit" name="action" value="add_new_author">Add Author</button>
</form>

<?php
// Include the database connection file
require_once('./connection.php');

// Check if the form is submitted
if (isset($_POST['action']) && $_POST['action'] === 'add_new_author') {
    // Get the author details from the form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Validate input (you can add further validation if needed)
    if (!empty($first_name) && !empty($last_name)) {
        // Prepare the SQL query to insert the new author
        $stmt = $pdo->prepare("INSERT INTO authors (first_name, last_name) VALUES (:first_name, :last_name)");
        $stmt->execute([
            'first_name' => $first_name,
            'last_name' => $last_name
        ]);

        // Redirect back to the book edit page after inserting the author
        header('Location: ./edit.php?id=' . $_GET['id']);
        exit();
    } else {
        // If the fields are empty, show an error (or handle it as needed)
        echo 'Please fill in both first and last name.';
    }
}
?>
