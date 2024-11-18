<?php

// add author to book
if ( isset($_POST['action']) && $_POST['action'] == 'add_author' ) {
    
    require_once('./connection.php');

    $bookId = $_POST['book_id'];
    $authorId = $_POST['author_id'];

    $stmt = $pdo->prepare('INSERT INTO book_authors (book_id, author_id) VALUES (:book_id, :auhtor_id);');
    $stmt->execute(['book_id' => $bookId, 'auhtor_id' => $authorId]);

    header("Location: ./edit.php?id={$bookId}");
    
} else {
    
    header("Location: ./index.php");
}