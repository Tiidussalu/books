<?php

// Include the database connection file
require_once('./connection.php');

// Retrieve the book ID from the URL's query parameter 'id'
$id = $_GET['id'];

// Fetch the book data based on the ID from the 'books' table
$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch(); // Store the book details in the $book variable

// Fetch the authors associated with this book using a LEFT JOIN between 'book_authors' and 'authors' tables
$bookAuthorsStmt = $pdo->prepare('SELECT * FROM book_authors ba LEFT JOIN authors a ON ba.author_id=a.id WHERE ba.book_id = :id');
$bookAuthorsStmt->execute(['id' => $id]);

// Fetch the available authors who are not already associated with this book
$availableAuthorsStmt = $pdo->prepare('SELECT * FROM authors WHERE id NOT IN (SELECT author_id FROM book_authors WHERE book_id = :book_id)');
$availableAuthorsStmt->execute(['book_id' => $id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - <?= htmlspecialchars($book['title']); ?></title>
    <style>
        /* General styling for the page */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        nav {
            margin-bottom: 20px;
        }
        nav a {
            text-decoration: none;
            color: #007BFF;
            font-size: 1rem;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #0056b3;
        }
        h3 {
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        form input[type="text"], form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        form button, form input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        form button:hover, form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
            padding: 10px;
            background: #fafafa;
            border: 1px solid #ddd;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        li button {
            background: none;
            border: none;
            cursor: pointer;
        }
        li button svg {
            fill: #dc3545;
            transition: fill 0.3s;
        }
        li button:hover svg {
            fill: #a71d2a;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Navigation to go back to the single book page -->
    <nav>
        <a href="./book.php?id=<?= $id; ?>">&#8592; Back to Book</a>
    </nav>

    <!-- Edit Book Form -->
    <h3>Edit Book</h3>
    <form action="./update_book.php?id=<?= $id; ?>" method="post">
        <!-- Input field for the book's title -->
        <label for="title">Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']); ?>">
        
        <!-- Input field for the book's price -->
        <label for="price">Price:</label>
        <input type="text" name="price" value="<?= number_format($book['price'], 2); ?>">
        
        <!-- Submit button to save changes -->
        <input type="submit" name="action" value="Save">
    </form>

    <!-- Display the authors associated with the book -->
    <h3>Authors</h3>
    <ul>
        <?php while ($author = $bookAuthorsStmt->fetch()) { ?>
            <li>
                <!-- Form to remove an author from the book -->
                <form action="./remove_author.php?id=<?= $id; ?>" method="post" style="display: inline;">
                    <span><?= htmlspecialchars($author['first_name']); ?> <?= htmlspecialchars($author['last_name']); ?></span>
                    <input type="hidden" name="author_id" value="<?= $author['id']; ?>">
                    <button type="submit" name="action" value="remove_author" aria-label="Remove Author">
                        <!-- Icon (SVG) to represent removing an author -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24">
                            <path d="M 10.806641 2 C 10.289641 2 9.7956875 2.2043125 9.4296875 2.5703125 L 9 3 L 4 3 A 1.0001 1.0001 0 1 0 4 5 L 20 5 A 1.0001 1.0001 0 1 0 20 3 L 15 3 L 14.570312 2.5703125 C 14.205312 2.2043125 13.710359 2 13.193359 2 L 10.806641 2 z M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 L 19.634766 7 L 4.3652344 7 z"></path>
                        </svg>
                    </button>
                </form>
            </li>
        <?php } ?>
    </ul>

    <!-- Form to add a new author to the book -->
    <form action="./add_author.php" method="post">
        <input type="hidden" name="book_id" value="<?= $id; ?>">
        
        <!-- Dropdown to select an author to add -->
        <label for="author_id">Add Author:</label>
        <select name="author_id">
            <option value="">Select an Author</option>
            <?php while ($author = $availableAuthorsStmt->fetch()) { ?>
                <option value="<?= $author['id']; ?>">
                    <?= htmlspecialchars($author['first_name']); ?> <?= htmlspecialchars($author['last_name']); ?>
                </option>
            <?php } ?>
        </select>
        
        <!-- Submit button to add the selected author -->
        <button type="submit" name="action" value="add_author">Add Author</button>
    </form>

    <!-- Search Form to Find Authors -->
<h3>Search Authors</h3>
<form action="./search_authors.php" method="get">
    <!-- Input field for the search query -->
    <label for="search_query">Author Name:</label>
    <input type="text" name="search_query" placeholder="Search by first or last name" required>

    <!-- Submit button to perform the search -->
    <button type="submit">Search</button>
</form>


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

</div>

</body>
</html>
