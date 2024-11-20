<?php

// Check if the 'action' is set in the POST request and if it equals 'add_author'
if ( isset($_POST['action']) && $_POST['action'] == 'add_author' ) {
    
    // Include the database connection file
    require_once('./connection.php');

    // Get the book ID and author ID from the POST request
    $bookId = $_POST['book_id'];
    $authorId = $_POST['author_id'];

    // Prepare the SQL query to insert the book-author relationship into the book_authors table
    $stmt = $pdo->prepare('INSERT INTO book_authors (book_id, author_id) VALUES (:book_id, :auhtor_id);');
    
    // Execute the query, binding the book ID and author ID to the SQL statement
    $stmt->execute(['book_id' => $bookId, 'auhtor_id' => $authorId]);

    // After executing the insertion, redirect the user to the 'edit.php' page for the specific book
    header("Location: ./edit.php?id={$bookId}");
    
} else {
    
    // If the 'action' is not 'add_author', redirect the user to the 'index.php' page
    header("Location: ./index.php");
}
?>