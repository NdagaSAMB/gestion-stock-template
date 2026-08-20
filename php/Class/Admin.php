<?php
define("FAUX_EMAIL", "FAUX_EMAIL");
define("FAUX_MDP", "FAUX_MDP");

class Admin {
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

    public static function estAdmin($email, $mdp) {
        $pdo = self::getConnection();
        
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return FAUX_EMAIL;
        }
        
        if ($result['mdp'] !== $mdp) {
            return FAUX_MDP;
        }
        
        return $result;
    }

    public static function afficher($table) {
        $pdo = self::getConnection();
        $stmt = $pdo->query("SELECT * FROM $table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function modifierAdmin($id, $nom, $prenom, $adr, $tele, $email, $mdp, $table) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("UPDATE $table SET nom = ?, prenom = ?, adr = ?, tele = ?, email = ?, mdp = ? WHERE id = ?");
        $stmt->execute([$nom, $prenom, $adr, $tele, $email, $mdp, $id]);
    }

    public static function modifierImageAdmin($id, $image) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("UPDATE admin SET image = ? WHERE id = ?");
        $stmt->execute([$image, $id]);
    }
}
