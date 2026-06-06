<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Creating database aish_catering...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS aish_catering");
    echo "Database created successfully.\n";
    
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
