<?php
$host = '127.0.0.1';
$db   = 'aishcatering';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Connected to $db successfully.\n";
     
     $stmt = $pdo->query("SHOW TABLES");
     $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
     
     if (empty($tables)) {
         echo "No tables found in $db.\n";
     } else {
         echo "Tables in $db:\n";
         foreach ($tables as $table) {
             echo "- $table\n";
         }
     }
} catch (\PDOException $e) {
     echo "Error: " . $e->getMessage() . "\n";
     echo "Code: " . $e->getCode() . "\n";
}
