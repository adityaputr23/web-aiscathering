<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Dropping database aishcatering...\n";
    $pdo->exec("DROP DATABASE IF EXISTS aishcatering");
    echo "Database dropped.\n";
    
    echo "Creating database aishcatering...\n";
    $pdo->exec("CREATE DATABASE aishcatering");
    echo "Database created successfully.\n";
    
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
