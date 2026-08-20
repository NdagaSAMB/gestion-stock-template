<?php

class Categorie {
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

    public static function afficher() {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT * FROM categorie");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouter($lib_cat, $desc_cat, $cat_image) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("INSERT INTO categorie (lib_cat, desc_cat, cat_image) VALUES (?, ?, ?)");
        $stmt->execute([$lib_cat, $desc_cat, $cat_image]);
    }

    public static function modifier($id_cat, $lib_cat, $desc_cat, $cat_image) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("UPDATE categorie SET lib_cat = ?, desc_cat = ?, cat_image = ? WHERE id_cat = ?");
        $stmt->execute([$lib_cat, $desc_cat, $cat_image, $id_cat]);
    }

    public static function supprimer($id_cat) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("DELETE FROM categorie WHERE id_cat = ?");
        $stmt->execute([$id_cat]);
    }
}
