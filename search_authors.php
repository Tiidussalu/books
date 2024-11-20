<?php

// Include the database connection file
require_once('./connection.php');

// Check if the search query is set
$searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Prepare the SQL query to search authors by name
// This query looks for authors whose first or last name contains the search term
$stmt = $pdo->prepare('
    SELECT * FROM authors 
    WHERE first_name LIKE :searchQuery1 OR last_name LIKE :searchQuery2
');
$stmt->execute(['searchQuery1' => '%' . $searchQuery . '%', 'searchQuery2' => '%' . $searchQuery . '%']); // Bind the search query to the statement

// Fetch the authors matching the search criteria
$authors = $stmt->fetchAll();

// Display the search results
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        /* You can add your CSS here to style the search results */
    </style>
</head>
<body>

<div class="container">
    <h3>Search Results</h3>
    
    <!-- Display a message if no authors were found -->
    <?php if (empty($authors)) { ?>
        <p>No authors found matching your search query.</p>
    <?php } else { ?>
        <ul>
            <?php foreach ($authors as $author) { ?>
                <li>
                    <?= htmlspecialchars($author['first_name']) ?> <?= htmlspecialchars($author['last_name']) ?>
                    <form action="./add_new_author.php" method="post" style="display: inline;">
                        <input type="hidden" name="book_id" value="<?= htmlspecialchars($bookId); ?>">
                        <input type="hidden" name="author_id" value="<?= $author['id']; ?>">
                        <button type="submit" name="action" value="add_author">Add Author</button>
                    </form>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

    <a href="./edit.php?id=<?= $bookId; ?>">Back to Book</a>
</div>

</body>
</html>
