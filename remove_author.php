<?php

// Check if the action is to remove an author
if (isset($_POST['action']) && $_POST['action'] === 'remove_author') {

    require_once('./connection.php');

    // Get the book ID from the URL
    $id = $_GET['id'] ?? null;

    if ($id && isset($_POST['author_id'])) {
        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare('DELETE FROM book_authors WHERE book_id = :book_id AND author_id = :author_id');
        $stmt->execute([
            'book_id' => $id,
            'author_id' => $_POST['author_id'],
        ]);

        // Redirect back to the edit page
        header("Location: ./edit.php?id={$id}");
        exit;
    } else {
        // Invalid request: missing ID or author ID
        header("Location: ./index.php");
        exit;
    }

} else {
    // Redirect to index if the action is invalid
    header("Location: ./index.php");
    exit;
}
