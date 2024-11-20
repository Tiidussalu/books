<?php

// Include the database connection file
require_once('./connection.php');

// Check if the 'action' is set in the POST request and if it equals 'Kustuta' (which means "Delete" in Estonian)
if ( isset($_POST['action']) && $_POST['action'] == 'Kustuta' ) {

    // Get the book ID from the POST request
    $id = $_POST['id'];

    // Prepare the SQL query to update the 'is_deleted' column to 1 (marking the book as deleted)
    $stmt = $pdo->prepare('UPDATE books SET is_deleted = 1 WHERE id = :id');
    
    // Execute the query, passing the book ID to the statement
    $stmt->execute(['id' => $id]);

    // Redirect the user to the index page after the book is marked as deleted
    header("Location: ./index.php");

}
?>
