<?php

class Sale {
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
        $stmt = $pdo->query("SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom FROM commande c LEFT JOIN client cl ON c.id_cli = cl.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function displaySale($num_com) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom FROM commande c LEFT JOIN client cl ON c.id_cli = cl.id WHERE c.num_com = ?");
        $stmt->execute([$num_com]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function displaySaleWithPr($num_com) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom FROM commande c LEFT JOIN client cl ON c.id_cli = cl.id WHERE c.num_com = ?");
        $stmt->execute([$num_com]);
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt2 = $pdo->prepare("SELECT cp.*, p.lib_pr, p.pr_image FROM contient_pr cp LEFT JOIN produit p ON cp.num_pr = p.num_pr WHERE cp.num_com = ?");
        $stmt2->execute([$num_com]);
        $products = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        
        return ['sale' => $sale, 'products' => $products];
    }

    public static function ajouter($num_com, $date_com, $id_cli) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("INSERT INTO commande (num_com, date_com, id_cli) VALUES (?, ?, ?)");
        $stmt->execute([$num_com, $date_com, $id_cli]);
    }

    public static function ajouterProduit($num_pr, $num_com, $qte_pr, $prix_vente) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("INSERT INTO contient_pr (num_pr, num_com, qte_pr, prix_vente) VALUES (?, ?, ?, ?)");
        $stmt->execute([$num_pr, $num_com, $qte_pr, $prix_vente]);
    }

    public static function supprimer($num_com) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("DELETE FROM commande WHERE num_com = ?");
        $stmt->execute([$num_com]);
    }

    public static function TotalLigne($table) {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public static function displayAllSales() {
        return self::afficher();
    }

    public static function deleteSale($num_com) {
        return self::supprimer($num_com);
    }

    public static function topSales() {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT p.num_pr, p.lib_pr, SUM(cp.qte_pr) as total_qte FROM contient_pr cp JOIN produit p ON cp.num_pr = p.num_pr GROUP BY p.num_pr, p.lib_pr ORDER BY total_qte DESC LIMIT 5");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
