<?php

// Update book data if form is submitted
if (isset($_POST['action']) && $_POST['action'] == 'Salvesta') {
    
    require_once('./connection.php');

    $id = $_GET['id'];

    // Check if title and price are set and valid
    $title = $_POST['title'];
    $price = $_POST['price'];

    if (empty($title) || empty($price)) {
        // Redirect back with an error if any field is empty
        header("Location: ./edit.php?id={$id}&error=empty_fields");
        exit;
    }

    // Ensure that the price is a valid number
    if (!is_numeric($price)) {
        // Redirect back with an error if price is not numeric
        header("Location: ./edit.php?id={$id}&error=invalid_price");
        exit;
    }

    // Prepare and execute the update query
    $stmt = $pdo->prepare('UPDATE books SET title = :title, price = :price WHERE id = :id');
    $stmt->execute([
        'id' => $id,
        'title' => $title,
        'price' => $price
    ]);

    // After updating, redirect back to the edit page
    header("Location: ./edit.php?id={$id}");
    exit; // Always use exit after header to stop further script execution
    
} else {
    // If the action is not "Salvesta", redirect to the edit page
    header("Location: ./edit.php?id={$_GET['id']}");
    exit;
}
