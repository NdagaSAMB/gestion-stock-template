<?php

class PrSale {
    private static $pdo;

    private static function getConnection() {
        if (!self::$pdo) {
            $host = 'localhost';
            $dbname = 'gestion_des_stocks';
            $username = 'root';
            $password = '';
            
            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    public static function afficher($num_com) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT cp.*, p.lib_pr, p.pr_image FROM contient_pr cp LEFT JOIN produit p ON cp.num_pr = p.num_pr WHERE cp.num_com = ?");
        $stmt->execute([$num_com]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
