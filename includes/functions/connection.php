<?php
require("constants.php");

function createConnection(): PDO 
{
    try {
        // Connect to MySQL using PDO
        $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME;
        $connection = new PDO($dsn, DB_USER, DB_PASS);

        // Set the PDO error mode to exception
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;
    } catch (PDOException $e) {
        // Catch any connection errors and display the message
        die("Failed to connect to MySQL: " . $e->getMessage());
    }
}

// router dynamic links causes issues so this is so I don't have to change link multiple places
$GLOBALS['base_url'] = "http://localhost/project-cinema";
// Also making a var because at first I didn't make it a global, so I don't have to change my echos in multiple places
$base_url = $GLOBALS['base_url'];