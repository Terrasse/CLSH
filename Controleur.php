<?php
require_once ('Vue.php');
require_once ('Inscription.php');
require_once ('Famille.php');
require_once ('Site.php');
class Controleur {
	private static $fonctionAutorise = array('offre'=>'construirePageOffre','site'=>'construirePageSite','unite' => 'contruirePageUnite','test'=>'contruirePageTest','famille'=>'contruirePageFamille');
	public function analyseURL() {
		if (count($_REQUEST) != 0) {
			if (array_key_exists($_REQUEST['action'], self::$fonctionAutorise)) {
				$methode = self::$fonctionAutorise[$_REQUEST['action']];
				$this -> $methode();
			} else {
				$methode = self::$fonctionAutorise['test'];
				$this -> $methode();
			}
		} else {
			$methode = self::$fonctionAutorise['test'];
			$this -> $methode();
		}
	}
	
	public function construirePageOffre(){
		$page=Site::findAll();
		$vue=new Vue($page);
		echo $vue-> affiche("disponibilite");
	}
	
	public function construirePageSite(){
		$page=Site::findAll();
		$vue = new Vue($page);
		echo $vue -> affiche("site");
	}
	
	public function contruirePageUnite() {
		$page = Unite::getPage();
		$vue = new Vue($page);
		echo $vue -> affiche("unite");
	}
	
	public function contruirePageFamille(){
		//TODO utiliser le formulaire post
		$page = Famille::findByNum($_REQUEST['id']);
		$vue = new Vue($page);
		echo $vue -> affiche("famille");
	}
	
	public function contruirePageTest(){
		$page = null;
		$vue = new Vue($page);
		echo $vue -> affiche("listetest");
	}
	
	public function traitementErreur($type=null,$cause){
		
	}
}
?>