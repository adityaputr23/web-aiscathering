<?php
$host = '127.0.0.1';
$db   = 'aish_catering';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Attempting to create a test table in $db...\n";
    $pdo->exec("CREATE TABLE test_table (id INT PRIMARY KEY)");
    echo "Test table created successfully.\n";
    
    $pdo->exec("DROP TABLE test_table");
    echo "Test table dropped successfully.\n";
    
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
}
