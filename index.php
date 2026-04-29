<?php
	session_start();
	// Test de connexion à la base
	$config = parse_ini_file('config.ini');

	$host = $config['host'];
	$user = $config['user'];
	$pass = $config['pass'];
	$db = $config['db'];

	// Chargement des fichiers MVC
	require("controler/controleur.php");
	require("view/vue.php");
	require("model/utilisateur.php");
	require("model/client.php");
	require("model/vendeur.php");
	require("model/presence.php");
	require("model/lieu.php");
	require("model/horaireHebdo.php");

	$controleur = new Controleur();
	$action = $_GET["action"] ?? "accueil";
	// Routes
	if(isset($_GET["action"])) {
		switch($_GET["action"]) {
			case "accueil":
				$controleur->accueil();
				break;

			case "connexion":
				$controleur->connexion();
				break;

			case "inscription":
				$controleur->inscription();
				break;

			case 'admin': 
				$controleur->pageAdmin(); 
				break;

			case 'validerVendeur':
				$controleur->validerVendeur($_GET['idV']);
				break;

			case 'refuserVendeur':
				$controleur->refuserVendeur($_GET['idV']);
				break;

			case 'vendeur': 
				$controleur->pageVendeur(); 
				break;
			
			case 'formulairePresence':
				$controleur->formulairePresence();
				break;

			case 'ajouterHoraireHebdo':
				$controleur->ajouterHoraireHebdo();
				break;

			case 'formulaireAjouterHoraireHebdo':
				$controleur->formulaireAjouterHoraireHebdo();
				break;

			case 'supprimerHoraireHebdo':
				$controleur->supprimerHoraireHebdo();
				break;

			case 'formulaireModifierHoraireHebdo':
				$controleur->formulaireModifierHoraireHebdo();
				break;

			case 'modifierHoraireHebdo':
				$controleur->modifierHoraireHebdo();
				break;

			case "changerStatutPresence":
				if (isset($_GET['id'])) {
					$controleur->changerStatutPresence($_GET['id']);
				}
				break;

			case 'formulaireModifierPresence':
				$controleur->formulaireModifierPresence();
				break;

			case 'modifierPresence':
				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
					$controleur->modifierPresence();
				}
				break;

			case 'supprimerPresence':
				$controleur->supprimerPresence();
				break;


			case "ajouterLieu":
				$controleur->ajouterLieu();
				break;

			case "listeLieux":
				$controleur->listeLieux();
				break;
			case 'geocodageInverse':
				$controleur->geocodageInverse();
				break;
		
			case "ajouterPresence":
				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
					$controleur->ajouterPresence();
				}
				break;

			case 'client': 
				$controleur->pageClient(); 
				break;

			case "deconnexion":
				(new controleur)->deconnexion();
				break;
			
			default:
				if(method_exists($controleur, 'erreur404')) {
					$controleur->erreur404();
				} else {
					$controleur->accueil();
				}
				break;
		}
	}
	else {
		$controleur->accueil();
	}

	

?>
