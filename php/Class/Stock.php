<?php

class Stock
{
    private static $pdo;

    // Constantes des types de mouvement
    const ENTREE_ACHAT = 'entree_achat';
    const ENTREE_INITIALE = 'entree_initiale';
    const SORTIE_VENTE = 'sortie_vente';
    const RETOUR_CLIENT = 'retour_client';
    const RETOUR_FOURNISSEUR = 'retour_fournisseur';
    const AJUSTEMENT_INVENTAIRE = 'ajustement_inventaire';
    const AJUSTEMENT_MANUEL = 'ajustement_manuel';
    const PERTE = 'perte';

    private static function getConnection()
    {
        if (!self::$pdo) {
            $host = 'localhost';
            $dbname = 'gestion_des_stocks';
            $username = 'root';
            $password = '';

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Échec de la connexion : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    // Enregistre un mouvement et met à jour le stock du produit en conséquence
    public static function addMouvement($num_pr, $type_mvt, $quantite, $doc_origine = null, $commentaire = null)
    {
        $pdo = self::getConnection();

        $stmt = $pdo->prepare("INSERT INTO mouvement_stock (num_pr, type_mvt, quantite, doc_origine, commentaire) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$num_pr, $type_mvt, $quantite, $doc_origine, $commentaire]);

        // Mise à jour du stock : + si entrée, - si sortie
        $sens = self::estEntree($type_mvt) ? '+' : '-';
        $pdo->prepare("UPDATE produit SET qte_stock = qte_stock $sens ? WHERE num_pr = ?")
            ->execute([$quantite, $num_pr]);
    }

    public static function tousLesMouvements()
    {
        $pdo = self::getConnection();
        $stmt = $pdo->query("
            SELECT m.*, p.lib_pr, p.pr_image, p.unite_mesure
            FROM mouvement_stock m
            JOIN produit p ON m.num_pr = p.num_pr
            ORDER BY m.date_mvt DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function historiqueProduit($num_pr)
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("
            SELECT m.*, p.lib_pr, p.pr_image, p.unite_mesure
            FROM mouvement_stock m
            JOIN produit p ON m.num_pr = p.num_pr
            WHERE m.num_pr = ?
            ORDER BY m.date_mvt DESC
        ");
        $stmt->execute([$num_pr]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function produitsSousSeuil()
    {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT * FROM produit WHERE qte_stock < 10 ORDER BY qte_stock ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function produitsProchesPeremption($jours)
    {
        return [];
    }

    public static function valeurStock()
    {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT SUM(prix_uni * qte_stock) as total FROM produit");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public static function libelleType($type)
    {
        $labels = [
            self::ENTREE_ACHAT => 'Entrée (achat)',
            self::ENTREE_INITIALE => 'Entrée (stock initial)',
            self::SORTIE_VENTE => 'Sortie (vente)',
            self::RETOUR_CLIENT => 'Retour client',
            self::RETOUR_FOURNISSEUR => 'Retour fournisseur',
            self::AJUSTEMENT_INVENTAIRE => 'Ajustement (inventaire)',
            self::AJUSTEMENT_MANUEL => 'Ajustement manuel',
            self::PERTE => 'Perte',
        ];
        return $labels[$type] ?? $type;
    }

    public static function estEntree($type)
    {
        return in_array($type, [
            self::ENTREE_ACHAT,
            self::ENTREE_INITIALE,
            self::RETOUR_CLIENT,
        ]);
    }
}
