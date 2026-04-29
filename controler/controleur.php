<?php
class Controleur {
	private $vue;
    private $presence;
    private $lieu;
    private $horaireHebdo;

    public function __construct() {
        $this->vue = new Vue();
        $this->presence = new Presence();
        $this->lieu = new Lieu();
        $this->horaireHebdo = new HoraireHebdo();
    }

    public function accueil() {
		if (isset($_SESSION['connexion'])) {
			$role = $_SESSION['connexion']['role'];

			switch ($role) {
				case 'admin':
					header("Location: index.php?action=admin");
					exit;

				case 'vendeur':
					header("Location: index.php?action=vendeur");
					exit;

				case 'client':
					header("Location: index.php?action=client");
					exit;

				default:
					$vue = new Vue();
					$vue->accueil();
					break;
			}
		} 
		else {
			$vue = new Vue();
			$vue->accueil();
		}
	}

    public function erreur404() {
        $vue = new Vue();
        $vue->erreur404();
    }

    public function inscription() {
		$vue = new Vue();
		$clientModel = new Utilisateur();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (!empty($_POST['mdp']) && $_POST['mdp'] === ($_POST['mdp2'] ?? '')) {
				if ($clientModel->emailExiste($_POST["email"])) {
					echo "<p style='color:red'>Cette adresse email est déjà utilisée.</p>";
					$vue->inscription();
					return;
				}

				try {
					$idUtilisateur = $clientModel->ajouterUtilisateur(
						$_POST["nom"],
						$_POST["prenom"],
						$_POST["email"],
						$_POST["telephone"],
						$_POST["mdp"],
						$_POST["role"],
						$_POST["localisationClient"] ?? null,
						$_POST["nomFoodTruck"] ?? null
					);

					echo "<p style='color:green'>Inscription réussie !</p>";
					$this->accueil();

				} 
				catch (PDOException $e) {
					echo "<p style='color:red'>Erreur BDD : ".$e->getMessage()."</p>";
					$vue->inscription();
				}
			} 
			else {
				echo "<p style='color:red'>Les mots de passe ne correspondent pas.</p>";
				$vue->inscription();
			}
		} 
		else {
			$vue->inscription();
		}
	}

	private function deconnexionAuto() {
		session_start();

		if(isset($_SESSION['page_loaded'])) {
			$_SESSION = [];
			session_destroy();

			header("Location: index.php?controller=Utilisateur&action=login");
			exit();
		} 
		else {
			$_SESSION['page_loaded'] = true;
		}
	}

    public function connexion() {
		$vue = new Vue();
		$clientModel = new Utilisateur();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$email = $_POST["email"];
			$mdp   = $_POST["mdp"];

			$client = $clientModel->getByEmail($email);

			if ($client && password_verify($mdp, $client["mdp"])) {
				$_SESSION["connexion"] = $client;

				if ($client["role"] === "admin") {
					header("Location: index.php?action=admin");
					exit();
				} 
				elseif ($client["role"] === "vendeur") {
					$_SESSION["connexion"] = $client;
					$lieux = $this->lieu->getAll();

					if (empty($lieux)) {
						header("Location: index.php?action=ajouterLieu");
						exit;
					} 
					else {
						$this->pageVendeur();
					}
				}

				elseif ($client["role"] === "client") {
					header("Location: index.php?action=client");
					exit();
				} 
				else {
					header("Location: index.php?action=accueil");
					exit();
				}
			} else {
				echo "<p style='color:red;text-align:center;'>Email ou mot de passe incorrect.</p>";
				$vue->connexion();
			}
		} else {
			$vue->connexion();
		}
	}

	public function deconnexion() {
        session_unset();
        session_destroy();

        echo "<script>
                alert('Vous avez été déconnecté.');
                window.location.href = 'index.php?action=accueil';
              </script>";
        exit;
    }

	public function validerVendeur($idV) {
		if (empty($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'admin') {
			echo "<p style='color:red;text-align:center;'>Accès refusé.</p>";
			return;
		}

		$userModel = new Utilisateur();
		$userModel->validerVendeur($idV, $_SESSION['connexion']['idUtilisateur']);
		header("Location: index.php?action=admin");
		exit;
	}

	public function refuserVendeur($idV) {
		if (empty($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'admin') {
			echo "<p style='color:red;text-align:center;'>Accès refusé.</p>";
			return;
		}

		$userModel = new Utilisateur();
		$userModel->refuserVendeur($idV, $_SESSION['connexion']['idUtilisateur']);
		header("Location: index.php?action=admin");
		exit;
	}

	public function pageAdmin() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'admin') {
			echo "<p style='color:red;text-align:center;'>Accès refusé. Vous devez être administrateur.</p>";
			return;
		}

		$utilisateurModel = new Utilisateur();
		$vendeurs = $utilisateurModel->getAllVendeur();

		$vue = new Vue();
		$vue->pageAdmin($vendeurs);
	}

	public function pageVendeur() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red;text-align:center;'>Accès refusé.</p>";
			return;
		}

		$vendeur = $_SESSION['connexion'];
		$utilisateurModel = new Utilisateur();

		$estValide = $utilisateurModel->estValider($vendeur['idUtilisateur']);
		$this->horaireHebdo->genererPresencesPourVendeur($vendeur['idUtilisateur']);

		$dernierLieu = $this->lieu->dernierLieuAjouter($vendeur['idUtilisateur']);
		$idLieu = $_SESSION['dernierLieuAjoute'] ?? ($dernierLieu['idLieu'] ?? null);

		$presences = $this->presence->getByVendeur($vendeur['idUtilisateur']);
		$lieux = $this->lieu->getAll();
		$horairesHebdo = $this->horaireHebdo->getByVendeur($vendeur['idUtilisateur']);

		$this->vue->pageVendeur($estValide, $presences, $lieux, $horairesHebdo);
		unset($_SESSION['dernierLieuAjoute']);
	}

	public function ajouterHoraireHebdo() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red'>AccÃ¨s refusÃ©.</p>";
			return;
		}

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			header("Location: index.php?action=vendeur");
			exit;
		}

		$idUtilisateur = $_SESSION['connexion']['idUtilisateur'];
		$jourSemaine = $_POST['jourSemaine'] ?? null;
		$arrive = $_POST['arrive'] ?? null;
		$depart = $_POST['depart'] ?? null;
		$idLieu = $_POST['idLieu'] ?? null;

		if (!$jourSemaine || !$arrive || !$depart || !$idLieu) {
			echo "<p style='color:red'>Tous les champs de l'horaire hebdomadaire sont obligatoires.</p>";
			return;
		}

		$this->horaireHebdo->ajouterHoraire($idUtilisateur, $jourSemaine, $arrive, $depart, $idLieu);
		$this->horaireHebdo->genererPresencesPourVendeur($idUtilisateur);
		header("Location: index.php?action=vendeur");
		exit;
	}

	public function formulaireAjouterHoraireHebdo() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red'>Acc&egrave;s refus&eacute;.</p>";
			return;
		}

		$lieux = $this->lieu->getAll();
		$this->vue->formulaireAjouterHoraireHebdo($lieux);
	}

	public function supprimerHoraireHebdo() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red'>AccÃ¨s refusÃ©.</p>";
			return;
		}

		$idHoraire = $_GET['id'] ?? null;
		if (!$idHoraire) {
			echo "<p style='color:red'>ID d'horaire manquant.</p>";
			return;
		}

		$this->horaireHebdo->supprimerHoraire($idHoraire, $_SESSION['connexion']['idUtilisateur']);
		header("Location: index.php?action=vendeur");
		exit;
	}

	public function formulaireModifierHoraireHebdo() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red'>Acc&egrave;s refus&eacute;.</p>";
			return;
		}

		$idHoraire = $_GET['id'] ?? null;
		if (!$idHoraire) {
			echo "<p style='color:red'>ID d'horaire manquant.</p>";
			return;
		}

		$horaire = $this->horaireHebdo->getById($idHoraire, $_SESSION['connexion']['idUtilisateur']);
		if (!$horaire) {
			echo "<p style='color:red'>Horaire introuvable.</p>";
			return;
		}

		$lieux = $this->lieu->getAll();
		$this->vue->formulaireModifierHoraireHebdo($horaire, $lieux);
	}

	public function modifierHoraireHebdo() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red'>Acc&egrave;s refus&eacute;.</p>";
			return;
		}

		$idHoraire = $_GET['id'] ?? null;
		if (!$idHoraire || $_SERVER['REQUEST_METHOD'] !== 'POST') {
			echo "<p style='color:red'>Requ&ecirc;te invalide.</p>";
			return;
		}

		$jourSemaine = $_POST['jourSemaine'] ?? null;
		$arrive = $_POST['arrive'] ?? null;
		$depart = $_POST['depart'] ?? null;
		$idLieu = $_POST['idLieu'] ?? null;

		if (!$jourSemaine || !$arrive || !$depart || !$idLieu) {
			echo "<p style='color:red'>Tous les champs sont obligatoires.</p>";
			return;
		}

		$this->horaireHebdo->modifierHoraire(
			$idHoraire,
			$_SESSION['connexion']['idUtilisateur'],
			$jourSemaine,
			$arrive,
			$depart,
			$idLieu
		);
		$this->horaireHebdo->genererPresencesPourVendeur($_SESSION['connexion']['idUtilisateur']);
		header("Location: index.php?action=vendeur");
		exit;
	}

	public function changerStatutPresence($idPresence) {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red;text-align:center;'>Accès refusé.</p>";
			return;
		}

		try {
			$this->presence->changerStatut($idPresence);
			header("Location: index.php?action=vendeur");
			exit;
		} 
		catch (Exception $e) {
			echo "<p style='color:red;text-align:center;'>Erreur : " . $e->getMessage() . "</p>";
		}
	}

	public function ajouterPresence() {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			echo "<p style='color:red'>Méthode invalide.</p>";
			return;
		}

		if (!isset($_SESSION['connexion'])) {
			echo "<p style='color:red'>Vous devez être connecté.</p>";
			return;
		}

		$idUtilisateur = $_SESSION['connexion']['idUtilisateur'];
		$date = $_POST['date'] ?? null;
		$arrive = $_POST['arrive'] ?? null;
		$depart = $_POST['depart'] ?? null;
		$idLieu = $_POST['idLieu'] ?? null;

		if (!$date || !$arrive || !$depart || !$idLieu) {
			echo "<p style='color:red'>Tous les champs obligatoires doivent être remplis, y compris le lieu.</p>";
			return;
		}

		try {
			$this->presence->ajouterPresence($idUtilisateur, $date, $arrive, $depart, $idLieu);
			header("Location: index.php?action=vendeur");
			exit;
		} 
		catch (Exception $e) {
			echo "<p style='color:red'>Erreur lors de l’ajout de la présence : ".$e->getMessage()."</p>";
		}
	}

	public function formulairePresence() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red;text-align:center;'>Accès refusé.</p>";
			return;
		}

		$lieux = $this->lieu->getAll();
		$this->vue->formulairePresence($lieux);
	}

	public function formulaireModifierPresence() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red;text-align:center;'>Accès refusé.</p>";
			return;
		}

		$id = $_GET['id'] ?? null;
		if (!$id) {
			$this->erreur404();
			return;
		}

		$presence = $this->presence->getById($id);
		if (!$presence) {
			$this->erreur404();
			return;
		}
		$lieux = $this->lieu->getAll();
		$this->vue->formulaireModifierPresence($presence, $lieux);
	}

	public function modifierPresence() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red'>Accès refusé.</p>";
			return;
		}

		$id = $_GET['id'] ?? null;
		if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->erreur404();
			return;
		}

		$date = $_POST['date'] ?? null;
		$arrive = $_POST['arrive'] ?? null;
		$depart = $_POST['depart'] ?? null;
		$idLieu = $_POST['idLieu'] ?? null;

		if (!$date || !$arrive || !$depart || !$idLieu) {
			echo "<p style='color:red'>Tous les champs sont obligatoires.</p>";
			return;
		}

		$this->presence->modifier($id, $date, $arrive, $depart, $idLieu);
		header("Location: index.php?action=vendeur");
		exit;
	}

	public function supprimerPresence() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red'>Accès refusé.</p>";
			return;
		}

		$id = $_GET['id'] ?? null;
		if (!$id) {
			echo "<p style='color:red'>ID manquant.</p>";
			return;
		}

		$this->presence->supprimerPresence($id);
		header("Location: index.php?action=vendeur");
		exit;
	}

	public function pageClient() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'client') {
			echo "<p style='color:red;text-align:center;'>Accès refusé.</p>";
			return;
		}

		$presenceModel = new Presence();
		$this->horaireHebdo->genererToutesLesPresences();
		$vendeursActifs = $presenceModel->getVendeursActifs();
		$utilisateurModel = new Utilisateur();
		$villeClient = $utilisateurModel->getLocalisationClient($_SESSION['connexion']['idUtilisateur']);
		$vue = new Vue();
		$vue->pageClient($vendeursActifs, $villeClient);
	}

	public function listeLieux() {
		$this->vue = new Vue();
        $this->lieu = new Lieu();

        $lieux = $this->lieu->getAll();
        $this->vue->pageLieux($lieux);
    }

    public function ajouterLieu() {
		if (!isset($_SESSION['connexion']) || $_SESSION['connexion']['role'] !== 'vendeur') {
			echo "<p style='color:red'>Accès refusé.</p>";
			return;
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$cp = $_POST['cp'];
			$ville = $_POST['ville'];
			$rue = $_POST['rue'];
			$coordLat = $_POST['coordLat'] ?? null;
			$coordLong = $_POST['coordLong'] ?? null;

			if ($coordLat === null || $coordLong === null || $coordLat === '' || $coordLong === '') {
				echo "<p style='color:red'>Veuillez sélectionner un point sur la carte.</p>";
				$this->vue->ajouterLieu();
				return;
			}

			$idLieu = $this->lieu->ajouterLieu($cp, $ville, $rue, $coordLat, $coordLong);
			$_SESSION['dernierLieuAjoute'] = $idLieu;
			header("Location: index.php?action=formulairePresence");
			exit;
		} 
		else {
			$this->vue->ajouterLieu();
			exit;
		}
	}

	public function geocodageInverse() {
		if (!isset($_GET['lat']) || !isset($_GET['lon'])) {
			echo json_encode(['error' => 'Coordonnées manquantes']);
			return;
		}

		$lat = $_GET['lat'];
		$lon = $_GET['lon'];
		$url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}&addressdetails=1";

		$opts = [
			"http" => [
				"header" => "User-Agent: FoodTruckApp/1.0\r\n"
			]
		];

		$context = stream_context_create($opts);
		$result = file_get_contents($url, false, $context);

		if (!$result) {
			echo json_encode(['error' => 'Erreur API']);
			return;
		}

		$json = json_decode($result, true);
		$addr = $json["address"] ?? [];

		echo json_encode([
			"rue"  => $addr["road"]  ?? "",
			"ville" => $addr["city"] ?? $addr["town"] ?? $addr["village"] ?? "",
			"cp"   => $addr["postcode"] ?? ""
		]);
	}
}
?>
