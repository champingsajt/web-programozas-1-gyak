<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS procrastinator_db DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
    $pdo->exec("USE procrastinator_db");

    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )");

    // Create counters table
    $pdo->exec("CREATE TABLE IF NOT EXISTS counters (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(100) NOT NULL,
        deadline DATETIME NOT NULL,
        status_message VARCHAR(255),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    echo "Database and tables created successfully. Please <a href='index.php'>go to index</a> and delete this file.";
} catch (PDOException $e) {
    die("Database initialization failed: " . $e->getMessage());
}
?>
