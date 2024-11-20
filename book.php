<?php

// Include the database connection file
require_once('./connection.php');

// Get the book ID from the query string (URL parameter)
$id = $_GET['id'];

// Prepare an SQL query to select the book details based on the book ID
$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
// Fetch the book details from the database
$book = $stmt->fetch();

// Prepare an SQL query to select the authors for the specific book by joining book_authors and authors tables
$stmt = $pdo->prepare('SELECT * FROM book_authors ba LEFT JOIN authors a ON ba.author_id=a.id WHERE ba.book_id = :id');
$stmt->execute(['id' => $id]);
// The result will be the list of authors associated with the book

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']); ?></title> <!-- Display the book title in the browser's title bar -->
    <style>
        /* Reset and global styles for the page */
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
        .title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .authors-list {
            list-style-type: none;
            padding: 0;
            margin: 0 0 20px;
        }
        .authors-list li {
            padding: 10px;
            margin-bottom: 5px;
            background: #fafafa;
            border-radius: 6px;
            transition: background 0.3s, transform 0.2s;
        }
        .authors-list li:hover {
            background: #e9ecef;
            transform: scale(1.02); /* Add a scaling effect on hover */
        }
        .price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #28a745; /* Green color for the price */
            margin-top: 10px;
        }
        .actions a, .actions form input[type="submit"] {
            display: inline-block;
            text-decoration: none;
            background: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        .actions a:hover, .actions form input[type="submit"]:hover {
            background: #0056b3; /* Darker blue on hover */
        }
        .actions form {
            display: inline-block;
            margin: 0;
        }

        /* Style for the Back button */
        .back-button {
            display: inline-block;
            background-color: #ccc;
            color: #333;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            border: none; /* Remove border */
        }
        
        .back-button:hover {
            background-color: #999; /* Darker gray when hovering */
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Display the book title -->
    <h1 class="title"><?= htmlspecialchars($book['title']); ?></h1>

    <!-- Section displaying authors associated with the book -->
    <h2>Authors:</h2>
    <ul class="authors-list">
        <?php 
        // Loop through the authors returned by the query
        while ($author = $stmt->fetch()) { ?>
            <li>
                <!-- Display first name and last name of each author -->
                <?= htmlspecialchars($author['first_name']); ?> <?= htmlspecialchars($author['last_name']); ?>
            </li>
        <?php } ?>
    </ul>

    <!-- Display the book price, rounded to 2 decimal places -->
    <p class="price">Price: <?= number_format($book['price'], 2); ?> &euro;</p>

    <!-- Action button for editing the book -->
    <div class="actions">
        <a href="./edit.php?id=<?= $id; ?>">Edit</a>
    </div>

    <!-- Back button to return to the list of books -->
    <button class="back-button" onclick="window.location.href='./index.php';">Back to Books List</button>
</div>

</body>
</html>
