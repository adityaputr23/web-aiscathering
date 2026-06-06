<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Creating database aish_new...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS aish_new");
    echo "Database created successfully.\n";
    
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
