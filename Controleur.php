<?php
require_once ('Vue.php');
require_once ('Inscription.php');
class Controleur{
	
	
	public function analyseURL(){
		//TODO faire un tableau + function pour chaque page
		$this->getEcranUnite();
	}
	
	public function getEcranUnite(){
		$page = Inscription::getPage();
		$vue = new Vue($page);
		echo $vue->affiche("unite");
	}
	
}

?>