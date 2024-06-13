<?php
    namespace Services;

    use PDO;

    function db() {
        try {
            $pdo = new PDO('mysql:host=db;dbname=prp', 'prp', 'prp');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        }
        catch (\PDOException $error) {
            die('Failed to connect to MySQL: ' . $error);
        }
    }
?>