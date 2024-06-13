<?php
    namespace Services;

    use PDO;

    function db() {
        try {
            return new PDO('mysql:host=db;dbname=prp', 'prp', 'prp');
        }
        catch (\PDOException $error) {
            die('Failed to connect to MySQL: ' . $error);
        }
    }
?>