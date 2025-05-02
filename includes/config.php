<?php
$host = 'localhost';
$dbname = 'cems'; // Change to your actual database name
$username = 'root';
$password = ''; // Default XAMPP password is empty

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
