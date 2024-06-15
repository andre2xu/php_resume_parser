<?php
    // create SQL tables
    try {
        $db = new PDO('mysql:host=db;port=3306;dbname=prp', 'prp', 'prp');

        $db->exec('
            DROP TABLE IF EXISTS users;
            CREATE TABLE users (
                id int NOT NULL AUTO_INCREMENT,
                email varchar(255) NOT NULL,
                password varchar(64) NOT NULL,
                PRIMARY KEY (id)
            );
        ');
    }
    catch (\PDOException $error) {
        die('Failed to create database tables: ' . $error);
    }
?>