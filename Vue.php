<?php
class Vue {
	private $pages;
	private static $contenuAutorise = array('famille'=>array ('status'=>'getStatusFamille','contenu'=>'getContenuFamille'),'listetest'=>array('status'=>'getStatusTest','contenu'=>'getContenuTest'));

	public function __construct($page) {
		$this -> pages = $page;
	}

	public function affiche($selecteur) {
		if (!isset($selected)) {
			if (array_key_exists($selecteur, self::$contenuAutorise)) {
				$fonctionPage = self::$contenuAutorise[$selecteur]['contenu'];
				$fonctionStatus = self::$contenuAutorise[$selecteur]['status'];
				$contenuPage=$this->$fonctionPage();
				$contenuStatus=$this->$fonctionStatus();
				return $this -> afficheGeneral($contenuPage,$contenuStatus);
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
	// les methodes suivantes gèrent le contenu de l'affichage des TESTs
	
	private function getStatusTest(){
		return "Affichage de la page de test";
	}
	
	private function getContenuTest(){
		
		return "
		<a href='index.php?action=famille&id=1'>afficher la famille n°1</a><br>
		<a href='index.php?action=famille&id=2'>afficher la famille n°1</a> 
		";
	}
	
	
	// les methodes suivantes gèrent le contenu de l'affichage des familles
	
	/**
	 * Methode permettant de generer le fragement status de la page Famille
	 */
	private function getStatusFamille(){
	 	return "STATUS EN CONSTRUCTION !";
	}
	
	private function getContenuFamille(){
		if($this->pages->getAttr('no_fam')!=null){
		return "
 		<form action='wiki.php?action=famille&type=edit' method='post'>
			<p>Information sur le responsable : <br>
    			</br>
    				<label for='nom_resp_new'>Nom :</label>
    				<input type='text' name='nom_resp_new' placeholder='necessaire' required/></br>
    				
    				<label for='pre_resp_new'>Prenom : </label>
    				<input type='text' name='pre_resp_new' placeholder='necessaire' required/></br>
    				<label for='type_resp_new'>Type : </label>		
             		<select name='type_resp_new' id='type_resp_new'>
               			<option value='Femme'>Femme</option>
                   		<option value='Homme'>Homme</option>
          		    </select></br>
					<label for='adr_resp_new'>Adresse : </label>
					<input type='text' name='adr_resp_new' placeholder='necessaire' required /></br>
					
					<label for='en_ville_new'>Ville : </label>
					<input type='text' name='en_ville_new' placeholder='necessaire' required /></br>
					
					<label for='tel_resp_new'>Téléphone : </label>
					<input type='text' name='tel_resp_new' placeholder='necessaire' required /></br>
					
					<label for='numalloc_caf_resp_new'>Numero d'allocataire : </label>
					<input type='text' name='numalloc_caf_resp_new' /></br>
		
					<label for='bons_vac_new'>Code promotionnel : </label>
					<input type='text' name='bons_vac_new'/></br>
    				<input type='submit' value='Valider'/>
			</p>
		</form>";
		}else{
				return"
 		<form action='wiki.php?action=famille&type=edit' method='post'>
			<p>Information sur le responsable : <br>
    			</br>
    				
    				<input type='text' name='nom_resp_new' placeholder='necessaire' required/>
    				</br><label for='nom_resp_new'>Nom :</label>
    				<input type='text' name='pre_resp_new' placeholder='necessaire' required/></br>
    				<label for='pre_resp_new'>Prenom : </label>
  					<select name='type_resp_new' id='type_resp_new required>
						<option value='Homme' checked>Homme</option>
						<option value='Femme'>Femme</option>
					</select>	
					<input type='text' name='adr_resp_new' placeholder='necessaire' required /></br><label for='adr_resp_new'>Adresse : </label>
					<input type='text' name='en_ville_new' placeholder='necessaire' required /></br><label for='en_ville_new'>Ville : </label>
					
					<input type='text' name='tel_resp_new' placeholder='necessaire' required /></br><label for='tel_resp_new'>Téléphone : </label>
				
					
					<input type='text' name='numalloc_caf_resp_new' /></br><label for='numalloc_caf_resp_new'>Numero d'allocataire : </label>
					<label for='bons_vac_new'>Code promotionnel : </label><input type='text' name='bons_vac_new'/></br>
					
    				<input type='submit' value='Valider'/>
			</p>
		</form>";
		}
		

	}
	
	/** 
	 * Methode permettant de genèrer le contenu de la page Famille
	 */
	 
	
	/**
	 * Methode permettant d'encapuler le contenu dynamic dans le contenu static
	 */

	private function afficheGeneral($contenu, $status) {
		$fragmenthtml = "
<html>
	<body>
		<div class='container'>\n";
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