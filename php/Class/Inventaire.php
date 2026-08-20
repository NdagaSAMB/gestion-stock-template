<?php

class Inventaire {
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
        $stmt = $pdo->query("SELECT p.*, c.lib_cat, m.nom_marque FROM produit p LEFT JOIN categorie c ON p.id_cat = c.id_cat LEFT JOIN marque m ON p.id_marque = m.id_marque ORDER BY p.qte_stock ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function afficher($id_inventaire) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT p.*, c.lib_cat, m.nom_marque FROM produit p LEFT JOIN categorie c ON p.id_cat = c.id_cat LEFT JOIN marque m ON p.id_marque = m.id_marque WHERE p.num_pr = ?");
        $stmt->execute([$id_inventaire]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function details($id_inventaire) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM produit WHERE num_pr = ?");
        $stmt->execute([$id_inventaire]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
