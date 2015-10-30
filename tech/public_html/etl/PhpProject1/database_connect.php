<?php
    $dsn = 'mysql:host=localhost;dbname=tech';
    $username = 'tech';
    $password = 'tech';

    try {
        $db = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('database_error.php');
        exit();
    }$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
?>