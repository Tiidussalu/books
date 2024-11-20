<?php

// Include the configuration file where the database credentials are stored
require_once('config.php');

// Create the Data Source Name (DSN) for the MySQL connection
// The DSN includes the host, database name, and character set to use
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Define options for the PDO connection
$options = [
    // Set the error mode to exception to catch any issues during database interaction
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    
    // Set the default fetch mode to associative array, meaning rows will be returned as associative arrays
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

    // Disable emulated prepared statements for better security and performance
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Create a new PDO instance to establish a connection to the MySQL database
// Pass the DSN, username, password, and options to the PDO constructor
$pdo = new PDO($dsn, $user, $pass, $options);

?>
