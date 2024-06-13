<?php
    // create SQL tables
    try {
        $db = new PDO('mysql:host=db;port=3306;dbname=prp', 'prp', 'prp');

        $db->exec('
            DROP TABLE IF EXISTS users;
            CREATE TABLE users (
                email varchar(255) NOT NULL,
                password varchar(64) NOT NULL
            );
        ');
    }
    catch (\PDOException $error) {
        die('Failed to create database tables: ' . $error);
    }
?>