<?php
class Database {
    public static function connect() {
        try {
            return new PDO("mysql:host=HOST;dbname=DBNAME;port=PORT;charset=utf8mb4","USER","PASS",[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die("Database Connection Failed");
        }
    }
}
?>
