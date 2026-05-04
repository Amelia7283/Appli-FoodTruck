<?php
class Lieu {
    private $db;

    public function __construct($pdo = null) {
        if ($pdo) {
            $this->db = $pdo;
        } else {
            $config = parse_ini_file("config.ini");
            $this->db = new PDO(
                "mysql:host=".$config['host'].";dbname=".$config['db'].";charset=utf8",
                $config['user'], $config['pass']
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        $this->ensureSchema();
    }

    private function ensureSchema() {
        $columns = $this->db->query("SHOW COLUMNS FROM Lieu")->fetchAll(PDO::FETCH_COLUMN);

        if (!in_array('idUtilisateur', $columns, true)) {
            $this->db->exec("ALTER TABLE Lieu ADD COLUMN idUtilisateur INT NULL");
            $this->db->exec("ALTER TABLE Lieu ADD INDEX idx_lieu_utilisateur (idUtilisateur)");
        }
    }

    public function ajouterLieu($cp, $ville, $rue, $coordLat, $coordLong, $idUtilisateur) {
        $stmt = $this->db->prepare("INSERT INTO Lieu (cp, ville, rue, coordLat, coordLong, idUtilisateur) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$cp, $ville, $rue, $coordLat, $coordLong, $idUtilisateur]);
        return $this->db->lastInsertId();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM Lieu ORDER BY ville, rue");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllByVendeur($idUtilisateur) {
        $stmt = $this->db->prepare("SELECT * FROM Lieu WHERE idUtilisateur = ? ORDER BY ville, rue");
        $stmt->execute([$idUtilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVillesByVendeur($idUtilisateur) {
        $stmt = $this->db->prepare("SELECT DISTINCT ville FROM Lieu WHERE idUtilisateur = ? AND ville IS NOT NULL AND ville <> '' ORDER BY ville");
        $stmt->execute([$idUtilisateur]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getById($idLieu) {
        $stmt = $this->db->prepare("SELECT * FROM Lieu WHERE idLieu = ?");
        $stmt->execute([$idLieu]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function supprimerLieu($idLieu) {
        $stmt = $this->db->prepare("DELETE FROM Lieu WHERE idLieu = ?");
        return $stmt->execute([$idLieu]);
    }

    public function modifierLieu($idLieu, $cp, $ville, $rue) {
        $stmt = $this->db->prepare("UPDATE Lieu SET cp = ?, ville = ?, rue = ? WHERE idLieu = ?");
        return $stmt->execute([$cp, $ville, $rue, $idLieu]);
    }

    public function dernierLieuAjouter($idUtilisateur) {
        $stmt = $this->db->prepare("SELECT * FROM Lieu WHERE idUtilisateur = ? ORDER BY idLieu DESC LIMIT 1");
        $stmt->execute([$idUtilisateur]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
