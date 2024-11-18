<?php
require_once('./connection.php');

// Handle the search query if present
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the SQL query to include search functionality
$stmt = $pdo->prepare('
    SELECT * FROM books 
    WHERE is_deleted = 0 AND title LIKE :searchQuery
');
$stmt->execute(['searchQuery' => '%' . $searchQuery . '%']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        h1 {
            font-family: 'Open Sans', sans-serif;
            color: #333;
            text-align: center;
            margin-top: 30px;
            font-size: 36px;
        }

        /* Container for the page */
        .container {
            padding: 20px;
        }

        /* Search bar */
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .search-container input {
            padding: 10px;
            width: 50%;
            max-width: 500px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .search-container button {
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #3498db;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            margin-left: 10px;
        }

        .search-container button:hover {
            background-color: #2980b9;
        }

        /* Book list container */
        .book-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Each book card */
        .book-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
            text-align: center;
            padding: 20px;
            position: relative;
        }

        .book-card:hover {
            transform: translateY(-10px);
        }

        .book-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 5px;
        }

        .book-card h3 {
            margin-top: 20px;
            font-size: 20px;
            color: #333;
            font-weight: 500;
        }

        .book-card p {
            color: #777;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .book-card a {
            display: inline-block;
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .book-card a:hover {
            background-color: #2980b9;
        }

        .book-card button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .book-card button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Books Collection</h1>

    <!-- Search Bar -->
    <div class="search-container">
        <form action="index.php" method="get">
            <input type="text" name="search" placeholder="Search books..." value="<?= htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Book List Container -->
    <div class="book-container">
        <?php while ($book = $stmt->fetch()) { ?>
            <div class="book-card">
                <!-- Book Image -->
                <img src="path/to/book-image.jpg" alt="Book Image">

                <!-- Book Title -->
                <h3><?= htmlspecialchars($book['title']); ?></h3>

                <!-- Book Description (or excerpt) -->
                <p><?= htmlspecialchars(substr($book['description'], 0, 100)) . '...'; ?></p>

                <!-- Link to View More Details -->
                <a href="./book.php?id=<?= $book['id']; ?>">View Details</a>

                <!-- Delete Button -->
                <form action="./delete.php" method="post" style="margin-top: 10px;">
                    <input type="hidden" name="id" value="<?= $book['id']; ?>">
                    <button type="submit" name="action" value="Kustuta">Delete</button>
                </form>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
