<?php

class Product {
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
        $stmt = $pdo->query("SELECT p.*, c.lib_cat, m.nom_marque FROM produit p LEFT JOIN categorie c ON p.id_cat = c.id_cat LEFT JOIN marque m ON p.id_marque = m.id_marque");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function displayPr($num_pr) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT p.*, c.lib_cat, m.nom_marque FROM produit p LEFT JOIN categorie c ON p.id_cat = c.id_cat LEFT JOIN marque m ON p.id_marque = m.id_marque WHERE p.num_pr = ?");
        $stmt->execute([$num_pr]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function ajouter($num_pr, $id_cat, $id_marque, $lib_pr, $desc_pr, $prix_uni, $prix_achat, $qte_stock, $pr_image) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("INSERT INTO produit (num_pr, id_cat, id_marque, lib_pr, desc_pr, prix_uni, prix_achat, qte_stock, pr_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$num_pr, $id_cat, $id_marque, $lib_pr, $desc_pr, $prix_uni, $prix_achat, $qte_stock, $pr_image]);
    }

    public static function modifier($num_pr, $id_cat, $id_marque, $lib_pr, $desc_pr, $prix_uni, $prix_achat, $qte_stock, $pr_image) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("UPDATE produit SET id_cat = ?, id_marque = ?, lib_pr = ?, desc_pr = ?, prix_uni = ?, prix_achat = ?, qte_stock = ?, pr_image = ? WHERE num_pr = ?");
        $stmt->execute([$id_cat, $id_marque, $lib_pr, $desc_pr, $prix_uni, $prix_achat, $qte_stock, $pr_image, $num_pr]);
    }

    public static function supprimer($num_pr) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("DELETE FROM produit WHERE num_pr = ?");
        $stmt->execute([$num_pr]);
    }

    public static function deletePr($num_pr) {
        return self::supprimer($num_pr);
    }

    public static function prJoinCatJoinMarque() {
        return self::afficher();
    }
}
