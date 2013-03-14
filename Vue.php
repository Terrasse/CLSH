<?php
class Vue {
	private $pages;
	private static $contenuAutorise = array(
		 		'edit' => 'aff_edit',
		 		'create' => 'aff_create',
		 		'save' => 'aff_save',
		 		'preview' => 'aff_preview',
		 		'get' => 'aff_get',
		 		'getall' => 'aff_getall',
		 		'delete' => 'aff_delete',
		 		'index' => 'aff_index',
		 		'error' => 'aff_error');

	public function __construct($page) {
		$this -> pages = $page;
	}

	public function affiche($selecteur) {
		if (!isset($selected)) {
			if (array_key_exists($selecteur, self::$contenuAutorise)) {
				$contenu = self::$contenuAutorise[$selecteur];
				$methode = $contenu["methode"];
				$contenuPage = $this -> $methode();
				return $this -> afficheGeneral($contenuPage, $contenu["status"]);
			} else
				printf("en contruction");
			//TODO gererer les erreurs
		} else
			printf("en contruction");
	}

	public function getContenuUnite() {
		$resultat = "en contruction";
		return $resultat;
	}

	/**
	 * Methode permettant d'encapuler le contenu dynamic dans le contenu static
	 */

	private function afficheGeneral($contenu, $status) {
		$fragmenthtml = "
<html>
	<body>
		<div class='container'>\n";
		$fragmenthtml .= $this -> fragmentConnexion();
		$fragmenthtml .= $this -> fragmentHead();
		$fragmenthtml .= $this -> fragmentHeader();
		$fragmenthtml .= $this -> fragmentStatus($status);
		$fragmenthtml .= $this -> fragmentPage($contenu);
		$fragmenthtml .= $this -> fragmentFooter();
		$fragmenthtml .= "
		</div>
	</body>
</html>";
		return $fragmenthtml;
	}

	/**
	 * Methode qui permet de générer le status
	 * @param $status nom de l'action appliqué sur $this->pages
	 */

	private function fragmentStatus($status) {
		$fragmenthtml = "
			<div class=status>
				<p>" . $status . "</p>
			</div>";
		return $fragmenthtml;
	}

	// Les methodes suivantes sont utilisées par afficheGeneral()
	// pour construire le contenu commun à toutes les pages

	/**
	 * Methode qui permet de générer le head
	 *  celui-ci est un contenu commun à toutes les pages
	 */
	private function fragmentHead() {
		return "
			<head>
				<meta charset='utf-8'/>
				<link rel='stylesheet' href='style.css' />
			</head>";
	}

	/**
	 * Methode qui permet de générer le connect
	 * 	celui-ci est un contenu commun à toutes les pages
	 */

	private function fragmentConnexion() {
		$contenu .= "
			<div class='connect'>";
		if (isset($_SESSION['user_id'])) {
			$contenu .= "
				<p> Vous êtes connecté en tant que " . $_SESSION['user_id'] . "(<a href='wiki.php?action=user&title=deconnexion'>)</p>
			";
		} else {
			$contenu .= "
				<form action='wiki.php?action=user&title=connexion' method='post'>
					<p>
						<label for='user_id'><a href='wiki.php?action=user&title=enregistrement'>S'enregistrer</a> / Connexion : </label>
	    				<input type='email' name='user_id' placeholder='e-mail' required/>
						<input type='password' name='user_password' placeholder='mot de passe'/>
	    				<input type='submit' value='Valider' required */>
					</p>
				</form>
			";
		}
		$contenu .= "
			</div>";
		return $contenu;
	}

	/**
	 * Methode qui permet de générer le fragment qui a pour id: header
	 *  celui-ci est un contenu commun à toutes les pages
	 */
	private function fragmentHeader() {
		return "
			<header>
				<p></p>
			</header>";
	}

	/**
	 * Methode qui premet de générer le fragment qui a pour id: page
	 * 	celui-ci est un contenu dynamique cependant il est encapsulé
	 * 	graçe à cette methode dans un contenu qui lui est commun à
	 * 	toutes les pages
	 * @param $contenu contenu qui dépend de l'action
	 */

	private function fragmentPage($contenu) {
		return "
			<div class='contenu'>
				" . $contenu . "
			</div>\n";
	}

	/**
	 * Methode qui permet de générer le fragment qui a pour id: footer
	 *  celui-ci est un contenu static à toutes les pages
	 */
	private function fragmentFooter() {
		return "
			<footer>
				<p>Copyright : Terry DERVAUX</p>
			</div>";
	}

}
?>