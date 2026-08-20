<?php

class RetourFournisseur {
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

    public static function liste() {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT a.*, f.nom as fournisseur_nom, f.prenom as fournisseur_prenom FROM approvisionnement a LEFT JOIN fournisseur f ON a.id_four = f.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
