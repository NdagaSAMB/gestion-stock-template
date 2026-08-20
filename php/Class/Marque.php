<?php

class Marque {
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
        $stmt = $pdo->query("SELECT * FROM marque");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouter($nom_marque, $description_marque, $br_image) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("INSERT INTO marque (nom_marque, description_marque, br_image) VALUES (?, ?, ?)");
        $stmt->execute([$nom_marque, $description_marque, $br_image]);
    }

    public static function modifier($id_marque, $nom_marque, $description_marque, $br_image) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("UPDATE marque SET nom_marque = ?, description_marque = ?, br_image = ? WHERE id_marque = ?");
        $stmt->execute([$nom_marque, $description_marque, $br_image, $id_marque]);
    }

    public static function supprimer($id_marque) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("DELETE FROM marque WHERE id_marque = ?");
        $stmt->execute([$id_marque]);
    }
}
