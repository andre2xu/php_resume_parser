<?php
    // create SQL tables
    try {
        $db = new PDO('mysql:host=db;port=3306;dbname=prp', 'prp', 'prp');

        $db->exec('
            DROP TABLE IF EXISTS pdfs;
            DROP TABLE IF EXISTS users;

            CREATE TABLE users (
                id int NOT NULL AUTO_INCREMENT,
                email varchar(255) NOT NULL,
                password varchar(64) NOT NULL,
                PRIMARY KEY (id)
            );

            CREATE TABLE pdfs (
                user_id int NOT NULL,
                filename varchar(44) NOT NULL,
                alias varchar(255) NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id)
            );



            -- for development
            INSERT INTO users (email, password) VALUES
            ("a@a.com", "6b86b273ff34fce19d6b804eff5a3f5747ada4eaa22f1d49c01e52ddb7875b4b");
        ');
    }
    catch (\PDOException $error) {
        die('Failed to create database tables: ' . $error);
    }
?>