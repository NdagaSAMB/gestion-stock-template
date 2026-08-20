<?php

class Purchase {
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
        $stmt = $pdo->query("SELECT a.*, f.nom as fournisseur_nom, f.prenom as fournisseur_prenom FROM approvisionnement a LEFT JOIN fournisseur f ON a.id_four = f.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function displayPurchase($num_app) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT a.*, f.nom as fournisseur_nom, f.prenom as fournisseur_prenom FROM approvisionnement a LEFT JOIN fournisseur f ON a.id_four = f.id WHERE a.num_app = ?");
        $stmt->execute([$num_app]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function ajouter($num_app, $date_app, $id_four, $desc_app) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("INSERT INTO approvisionnement (num_app, date_app, id_four, desc_app) VALUES (?, ?, ?, ?)");
        $stmt->execute([$num_app, $date_app, $id_four, $desc_app]);
    }

    public static function ajouterProduit($num_app, $num_pr, $qte_achete) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("INSERT INTO est_compose (num_app, num_pr, qte_achete) VALUES (?, ?, ?)");
        $stmt->execute([$num_app, $num_pr, $qte_achete]);
    }

    public static function supprimer($num_app) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("DELETE FROM approvisionnement WHERE num_app = ?");
        $stmt->execute([$num_app]);
    }

    public static function TotalLigne($table) {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public static function displayAllPur() {
        return self::afficher();
    }

    public static function deletePur($num_app) {
        return self::supprimer($num_app);
    }
}
