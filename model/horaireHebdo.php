<?php
class HoraireHebdo {
    private $db;

    public function __construct($pdo = null) {
        if ($pdo) {
            $this->db = $pdo;
        } else {
            $config = parse_ini_file("config.ini");
            $this->db = new PDO(
                "mysql:host=".$config['host'].";dbname=".$config['db'].";charset=utf8",
                $config['user'],
                $config['pass']
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        $this->ensureTable();
    }

    private function ensureTable() {
        $sql = "CREATE TABLE IF NOT EXISTS HoraireHebdo (
                    idHoraire INT NOT NULL AUTO_INCREMENT,
                    idUtilisateur INT NOT NULL,
                    jourSemaine TINYINT NOT NULL,
                    arrive TIME NOT NULL,
                    depart TIME NOT NULL,
                    idLieu BIGINT UNSIGNED NOT NULL,
                    actif TINYINT(1) NOT NULL DEFAULT 1,
                    PRIMARY KEY (idHoraire),
                    KEY idx_horaire_utilisateur (idUtilisateur),
                    KEY idx_horaire_lieu (idLieu)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

        $this->db->exec($sql);
    }

    public function getByVendeur($idUtilisateur) {
        $stmt = $this->db->prepare(
            "SELECT HoraireHebdo.*, Lieu.rue, Lieu.cp, Lieu.ville
             FROM HoraireHebdo
             JOIN Lieu ON HoraireHebdo.idLieu = Lieu.idLieu
             WHERE HoraireHebdo.idUtilisateur = ?
             ORDER BY jourSemaine, arrive"
        );
        $stmt->execute([$idUtilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterHoraire($idUtilisateur, $jourSemaine, $arrive, $depart, $idLieu) {
        $stmt = $this->db->prepare(
            "INSERT INTO HoraireHebdo (idUtilisateur, jourSemaine, arrive, depart, idLieu, actif)
             VALUES (?, ?, ?, ?, ?, 1)"
        );
        return $stmt->execute([$idUtilisateur, $jourSemaine, $arrive, $depart, $idLieu]);
    }

    public function getById($idHoraire, $idUtilisateur) {
        $stmt = $this->db->prepare("SELECT * FROM HoraireHebdo WHERE idHoraire = ? AND idUtilisateur = ?");
        $stmt->execute([$idHoraire, $idUtilisateur]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifierHoraire($idHoraire, $idUtilisateur, $jourSemaine, $arrive, $depart, $idLieu) {
        $stmt = $this->db->prepare(
            "UPDATE HoraireHebdo
             SET jourSemaine = ?, arrive = ?, depart = ?, idLieu = ?
             WHERE idHoraire = ? AND idUtilisateur = ?"
        );
        return $stmt->execute([$jourSemaine, $arrive, $depart, $idLieu, $idHoraire, $idUtilisateur]);
    }

    public function supprimerHoraire($idHoraire, $idUtilisateur) {
        $stmt = $this->db->prepare("DELETE FROM HoraireHebdo WHERE idHoraire = ? AND idUtilisateur = ?");
        return $stmt->execute([$idHoraire, $idUtilisateur]);
    }

    public function genererPresencesPourVendeur($idUtilisateur, $daysAhead = 56) {
        $stmt = $this->db->prepare("SELECT * FROM HoraireHebdo WHERE idUtilisateur = ? AND actif = 1");
        $stmt->execute([$idUtilisateur]);
        $horaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($horaires)) {
            return;
        }

        $checkStmt = $this->db->prepare(
            "SELECT COUNT(*) FROM Presence
             WHERE idUtilisateur = ? AND date = ? AND arrive = ? AND depart = ? AND idLieu = ?"
        );
        $insertStmt = $this->db->prepare(
            "INSERT INTO Presence (date, arrive, depart, actif, idUtilisateur, idLieu)
             VALUES (?, ?, ?, 1, ?, ?)"
        );

        $today = new DateTimeImmutable('today');
        for ($i = 0; $i <= $daysAhead; $i++) {
            $date = $today->modify("+$i day");
            $jour = (int) $date->format('N');

            foreach ($horaires as $horaire) {
                if ((int) $horaire['jourSemaine'] !== $jour) {
                    continue;
                }

                $dateSql = $date->format('Y-m-d');
                $checkStmt->execute([
                    $idUtilisateur,
                    $dateSql,
                    $horaire['arrive'],
                    $horaire['depart'],
                    $horaire['idLieu']
                ]);

                if ((int) $checkStmt->fetchColumn() === 0) {
                    $insertStmt->execute([
                        $dateSql,
                        $horaire['arrive'],
                        $horaire['depart'],
                        $idUtilisateur,
                        $horaire['idLieu']
                    ]);
                }
            }
        }
    }

    public function genererToutesLesPresences($daysAhead = 56) {
        $stmt = $this->db->query("SELECT DISTINCT idUtilisateur FROM HoraireHebdo WHERE actif = 1");
        $vendeurs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($vendeurs as $idUtilisateur) {
            $this->genererPresencesPourVendeur((int) $idUtilisateur, $daysAhead);
        }
    }
}
?>
