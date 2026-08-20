<?php

class PrPurchase {
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

    public static function afficher($num_app) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT ec.*, p.lib_pr, p.pr_image FROM est_compose ec LEFT JOIN produit p ON ec.num_pr = p.num_pr WHERE ec.num_app = ?");
        $stmt->execute([$num_app]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function displayPrPurchase($num_app) {
        return self::afficher($num_app);
    }
}
