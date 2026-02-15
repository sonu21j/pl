<?php
class Database {
    public static function connect() {
        try {
            return new PDO("mysql:host=localhost;dbname=pl;port=3306;charset=utf8mb4","root","",[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die("Database Connection Failed");
        }
    }
}
?>
