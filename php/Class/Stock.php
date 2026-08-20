<?php

class Stock {
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

    public static function tousLesMouvements() {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT 'Entrée' as type, a.date_app as date, a.num_app as reference, f.nom as fournisseur_nom, ec.qte_achete as quantite, p.lib_pr as produit_lib FROM est_compose ec JOIN approvisionnement a ON ec.num_app = a.num_app JOIN fournisseur f ON a.id_four = f.id JOIN produit p ON ec.num_pr = p.num_pr UNION ALL SELECT 'Sortie' as type, c.date_com as date, c.num_com as reference, cl.nom as client_nom, cp.qte_pr as quantite, p.lib_pr as produit_lib FROM contient_pr cp JOIN commande c ON cp.num_com = c.num_com JOIN client cl ON c.id_cli = cl.id JOIN produit p ON cp.num_pr = p.num_pr ORDER BY date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function produitsSousSeuil() {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT * FROM produit WHERE qte_stock < 10 ORDER BY qte_stock ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function produitsProchesPeremption($jours) {
        // La base de données n'a pas de champ de date de péremption, retourner un tableau vide
        return [];
    }

    public static function valeurStock() {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT SUM(prix_uni * qte_stock) as total FROM produit");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public static function historiqueProduit($num_pr) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT 'Entrée' as type, a.date_app as date, a.num_app as reference, f.nom as fournisseur_nom, ec.qte_achete as quantite, p.lib_pr as produit_lib FROM est_compose ec JOIN approvisionnement a ON ec.num_app = a.num_app JOIN fournisseur f ON a.id_four = f.id JOIN produit p ON ec.num_pr = p.num_pr WHERE ec.num_pr = ? UNION ALL SELECT 'Sortie' as type, c.date_com as date, c.num_com as reference, cl.nom as client_nom, cp.qte_pr as quantite, p.lib_pr as produit_lib FROM contient_pr cp JOIN commande c ON cp.num_com = c.num_com JOIN client cl ON c.id_cli = cl.id JOIN produit p ON cp.num_pr = p.num_pr WHERE cp.num_pr = ? ORDER BY date DESC");
        $stmt->execute([$num_pr, $num_pr]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function libelleType($type) {
        return $type;
    }

    public static function estEntree($type) {
        return $type === 'Entrée';
    }
}
