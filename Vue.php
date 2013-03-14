<?php
class Vue {
	private $pages;
	private static $contenuAutorise = array('site'=>array('status'=>'getStatusSite','contenu'=>'getContenuSite'),
											'disponibilite'=>array('status'=>'getStatusDisponibilite','contenu'=>'getContenuDisponibilite'),
											'famille'=>array('status' => 'getStatusFamille','contenu' =>'getContenuFamille'),
											'listetest'=>array('status' => 'getStatusTest', 'contenu' => 'getContenuTest')
											);

	public function __construct($page) {
		$this -> pages = $page;
	}

	public function affiche($selecteur) {
		if (!isset($selected)) {
			if (array_key_exists($selecteur, self::$contenuAutorise)) {
				$fonctionPage = self::$contenuAutorise[$selecteur]['contenu'];
				$fonctionStatus = self::$contenuAutorise[$selecteur]['status'];
				$contenuPage = $this -> $fonctionPage();
				$contenuStatus = $this -> $fonctionStatus();
				return $this -> afficheGeneral($contenuPage, $contenuStatus);
			} else
				printf("en contruction");
			//TODO gererer les erreurs
		} else
			printf("en contruction");
	}
	// les methodes suivantes gèrent le contenu de l'affichage des Sites
	
	public function getStatusSite(){
		return "Page de selection du Site";
	}
	
	public function getContenuSite(){
		$resultat="
			<form method='post' action='index.php?action=offre'>
		   		<p>
		   		<label for='no_site_sel'>Selectionnez le site où vous souhaitez inscrire votre enfant</label></br>	
		   		<select name='no_site_sel' id='no_site_sel'>";
			foreach ($this->pages as $key => $value) {
				 $resultat.="<option value='".$value->getAttr('no_site')."'>".$value->getAttr('nom_site')."</option>";	
			}
		$resultat.="
				</select>
				</p>
		   		<input type='submit' value='Valider'/>
			</form>";
		return $resultat;
	}
	
	// les methodes suivantes gèrent le contenu de l'affichage des Unites
	
	public function getContenuUnite() {
		$resutat="
		<form method='post' action='index.php?action=disponibilite'>
		   <p>
		       <label for='unite'>Dans quel pays habitez-vous ?</label><br />
		       <select name='pays' id='pays'>
		           <option value='france'>France</option>
		           <option value='espagne'>Espagne</option>
		       </select>
		   </p>
		</form>";
		return $resultat;
	}
	
	// les methodes suivantes gèrent le contenu de l'affichage de disponibilités
	
	private function getStatusDisponibilite(){
		return "en Construction";
	}
	
	private function getContenuDisponibilite(){
			var_dump($this->pages);
			$resultat="
			<form method='post' action='index.php?action=disponibilite'>
		   		<p>
		   		Indiquez les périodes pour lequels vous souhaitez inscrire l'enfant ?
		   		<input type='hidden' name='no_site_sel' value='"+$_POST['no_site_sel']+"'>
		   	";
			foreach ($this->pages as $key => $value) {
				$nbPlaceDispo=$value->getAttr('nb_places_offertes')-$value->getAttr('nb_places_occupees');
				if($nbPlaceDispo>1){
					$semaine=Semaine::findByNum($value->getAttr('sem_sej'));
					$unite=Unite::findByNum($value->getAttr('no_unite'));
					$resultat.="<input type='checkbox' name='"+$value->getAttr('no_unite_sel')+"' id='"+$value->getAttr('no_unite_sel')+"' /> <label for='"+$value->getAttr('no_unite_sel')+"'>"+$unite->getAttr('nom_unite')+ " du "+$semaine->getAttr('du_sem')+" au "+$semaine->getAttr('au_sem')+" | nombre de place disponible : "+ $nbPlaceDispo +"</label>";
				}	
			}
			var_dump($resultat);
			$resultat.="
				<input type='submit' value='Valider'/>
				</p>
			</form>";
		return $resultat;
	}
	

	// les methodes suivantes gèrent le contenu de l'affichage des TESTs

	private function getStatusTest() {
		return "Affichage de la page de test";
	}

	private function getContenuTest() {

		return "
		<a href='index.php?action=famille&id=1'>afficher la famille n°1</a><br>
		<a href='index.php?action=famille&id=2'>afficher la famille n°2</a><br> 
		<a href='index.php?action=site'>afficher ecran selection site</a> 
		";
	}

	// les methodes suivantes gèrent le contenu de l'affichage des familles

	/**
	 * Methode permettant de generer le fragement status de la page Famille
	 */
	private function getStatusFamille() {
		return "STATUS EN CONSTRUCTION !";
	}

	private function getContenuFamille() {
		if ($this -> pages -> getAttr('no_fam') == '') {
			return "
 		<form action='index.php?action=save&type=create' method='post'>
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
		} else {
			return "
 		<form action='index.php?action=famille&type=edit' method='post'>
			<p>Information sur le responsable : <br>
    			</br>
    				<label for='nom_resp_new'>Nom :</label>
    				<input type='text' name='nom_resp_new' value='" . $this -> pages -> getAttr('nom_resp') . "' placeholder='necessaire' required/></br>
    				
    				<label for='pre_resp_new'>Prenom : </label>
    				<input type='text' name='pre_resp_new' value='" . $this -> pages -> getAttr('pre_resp') . "' placeholder='necessaire' required/></br>
    				<label for='type_resp_new'>Lien de parente : </label>		
					<input type='text' name='type_resp_new' value='" . $this -> pages -> getAttr('type_resp') . "' placeholder='necessaire' required/></br>
					<label for='adr_resp_new'>Adresse : </label>
					<input type='text' name='adr_resp_new' value='" . $this -> pages -> getAttr('adr_resp') . "' placeholder='necessaire' required /></br>
					
					<label for='en_ville_new'>Ville : </label>
					<input type='text' name='en_ville_new' value='" . $this -> pages -> getAttr('en_ville') . "' placeholder='necessaire' required /></br>
					
					<label for='tel_resp_new'>Téléphone : </label>
					<input type='tel' name='tel_resp_new' value='" . $this -> pages -> getAttr('tel_resp') . "' placeholder='necessaire' required /></br>
					
					<label for='numalloc_caf_resp_new'>Numero d'allocataire : </label>
					<input type='text' value='" . $this -> pages -> getAttr('numalloc_caf_resp') . "' name='numalloc_caf_resp_new' /></br>
		
					<label for='bons_vac_new'>Code promotionnel : </label>
					<input type='text' value='" . $this -> pages -> getAttr('bons_vac_new') . "' name='bons_vac_new'/></br>
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