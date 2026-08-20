<?php

class Supplier {
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
        $stmt = $pdo->query("SELECT * FROM fournisseur");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouter($nom, $prenom, $adr, $tele, $email, $image) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("INSERT INTO fournisseur (nom, prenom, adr, tele, email, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $adr, $tele, $email, $image]);
    }

    public static function modifier($id, $nom, $prenom, $adr, $tele, $email, $image) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("UPDATE fournisseur SET nom = ?, prenom = ?, adr = ?, tele = ?, email = ?, image = ? WHERE id = ?");
        $stmt->execute([$nom, $prenom, $adr, $tele, $email, $image, $id]);
    }

    public static function supprimer($id) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("DELETE FROM fournisseur WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function nbrDesTuples($table) {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
