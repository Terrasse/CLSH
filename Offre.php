<?php

/**
 *  La classe Offre
 *
 *  La Classe Offre  realise un Active Record sur la table Offre
 *  À TERMINER !!!!!!!!!!!!!!!!!
 *
 *  @package ACSI
 */

class Offre {

	private $no_unite;
	private $sem_sej;
	private $nb_places_offertes;
	private $nb_places_occupees;

	public function __construct() {
		// rien à faire
	}

	/**
	 *  Magic pour imprimer
	 *
	 *  Fonction Magic retournant une chaine de caracteres imprimable
	 *  pour imprimer facilement un Ouvrage
	 *
	 *  @return String
	 */
	public function __toString() {
		return "[" . __CLASS__ . "] nom : " . $this -> nom . "
				   prenom  " . $this -> prenom . "no_fact : " . $this -> no_fact;
	}

	/**
	 *   Getter generique
	 *
	 *   fonction d'acces aux attributs d'un objet.
	 *   Recoit en parametre le nom de l'attribut accede
	 *   et retourne sa valeur.
	 *
	 *   @param String $attr_name attribute name
	 *   @return mixed
	 */
	public function getAttr($attr_name) {
		if (property_exists(__CLASS__, $attr_name)) {
			return $this -> $attr_name;
		}
		$emess = __CLASS__ . ": unknown member $attr_name (getAttr)";
		throw new Exception($emess, 45);
	}

	/**
	 *   Setter generique
	 *
	 *   fonction de modification des attributs d'un objet.
	 *   Recoit en parametre le nom de l'attribut modifie et la nouvelle valeur
	 *
	 *   @param String $attr_name attribute name
	 *   @param mixed $attr_val attribute value
	 *   @return mixed new attribute value
	 */
	public function setAttr($attr_name, $attr_val) {
		if (property_exists(__CLASS__, $attr_name)) {
			$this -> $attr_name = $attr_val;
			return $this -> $attr_name;
		}
		$emess = __CLASS__ . ": unknown member $attr_name (setAttr)";
		throw new Exception($emess, 45);

	}

	/**
	 *   Sauvegarde dans la base
	 *
	 *   Enregistre l'etat de l'objet dans la table
	 *   Si l'objet possede un identifiant : mise à jour de la ligne correspondante
	 *   sinon :
	 * 	verification de l'existence de la page et eventuellement
	 * 	insertion dans une nouvelle ligne
	 *
	 *   @return int le nombre de lignes touchees
	 */
	public function save() {
		//si la page possède un id on met à jour
		if (isset($this -> no_unite) && isset($this -> sem_sej)) {
			return $this -> update();
		} else {
			return $this -> insert();
		}
	}

	/**
	 *   mise a jour de la ligne courante
	 *
	 *   Sauvegarde l'objet courant dans la base en faisant un update
	 *   le titre de la page doit exister (insert obligatoire auparavant)
	 *   méthode privée - la méthode publique s'appelle save
	 *   @acess public
	 *   @return int nombre de lignes mises à jour
	 */
	public function update() {

		if (!isset($this -> $nb_places_occupees)) {
			throw new Exception(__CLASS__ . ": Nombre de places occupees undefined : cannot update");
		}

		$pdo = Base::getConnection();

		//preparation de la requete
		$query = $pdo -> prepare("update Offre set nb_places_occupees=:$nb_places_occupees, 
				where no_unite=:no_unite AND sem_sej = :sem_sej");

		//liaison des parametres
		if (isset($this -> nb_places_occupees))
			$query -> bindParam(':nb_places_occupees', $this -> nb_places_occupees);
		else
			$query -> bindParam(':nb_places_occupees', "null", PDO::PARAM_STR);
		$delete -> bindParam(':no_unite', $this -> no_unite);
		$delete -> bindParam(':sem_sej', $this -> sem_sej);
		//lancement de la requete prépar
		$nb = $query -> execute();

		return $nb;

	}

	/**
	 *   Suppression dans la base
	 *
	 *   Supprime la ligne dans la table corrsepondant à l'objet courant
	 *   L'objet doit posséder un OID
	 */
	public function delete() {

		$pdo = Base::getConnection();

		if (isset($this -> no_unite) && isset($this -> sem_sej)) {
			$delete = $pdo -> prepare("DELETE FROM Offre WHERE no_unite = :no_unite AND sem_sej = :sem_sej");
			$delete -> bindParam(':no_unite', $this -> no_unite);
			$delete -> bindParam(':sem_sej', $this -> sem_sej);
			$nb = $delete -> execute();
		} else {
			$nb = '0';
		}
		//echo "Suppression OK </br></br>";
		return $nb;
	}

	/**
	 *   Insertion dans la base
	 *
	 *   Insère l'objet comme une nouvelle ligne dans la table
	 *   l'objet doit posséder un id
	 *
	 *   @return int nombre de lignes insérées
	 */
	public function insert() {

		$pdo = Base::getConnection();
		$insert = $pdo -> prepare("INSERT INTO Offre VALUES (:no_unite, :sem_sej, :nb_places_offertes, :nb_places_occupees)");

		if (isset($this -> no_unite)) {
			$insert -> bindParam(':no_unite', $this -> no_unite);
		} else {
			$insert -> bindParam(':no_unite', "null", PDO::PARAM_STR);
		}

		if (isset($this -> sem_sej)) {
			$insert -> bindParam(':sem_sej', $this -> sem_sej);
		} else {
			$insert -> bindParam(':sem_sej', "null", PDO::PARAM_STR);
		}

		if (isset($this -> $nb_places_offertes)) {
			$insert -> bindParam(':$nb_places_offertes', $this -> $nb_places_offertes);
		} else {
			$insert -> bindParam(':$nb_places_offertes', "null", PDO::PARAM_STR);
		}

		if (isset($this -> $nb_places_occupees)) {
			$insert -> bindParam(':$nb_places_occupees', $this -> $nb_places_occupees);
		} else {
			$insert -> bindParam(':$nb_places_occupees', "null", PDO::PARAM_STR);
		}

		$insert -> bindParam(':date_fact', date("Ymd"), PDO::PARAM_STR);

		$nb = $insert -> execute();
		$this -> update();

		return $nb;

	}

	/**
	 *   Finder sur ID
	 *
	 *   Retrouve la ligne de la table correspondant au ID passé en paramètre,
	 *   retourne un objet
	 *
	 *   @static
	 *   @param integer $id OID to find
	 *   @return Page renvoie un objet de type Page
	 */
	public static function findByUnite($id) {
		$pdo = Base::getConnection();

		//preparation de la requete
		$query = $pdo -> prepare("SELECT * FROM Offre WHERE no_unite=:id");
		$query -> bindParam(':id', $id);

		$dbres = $query -> execute();

		$t = $query -> fetch(PDO::FETCH_ASSOC);

		$tab = array();
		var_dump($t);
		foreach ($t as $tfact) {
			$tab[] = Offre::creerObjet($tfact);
		}

		return $tab;
	}

	/**
	 *   Finder sur title
	 *
	 *   Retrouve la ligne de la table correspondant au title passé en paramètre,
	 *   retourne un objet ou false si la page n'existe pas
	 *
	 *   @static
	 *   @param integer $id OID to find
	 *   @return Page renvoie un objet de type Page
	 */
	public static function findBySem($id) {

		$pdo = Base::getConnection();
		$query = $pdo -> prepare("SELECT * FROM Offre WHERE sem_sej=:id");
		$query -> bindParam(":id", $id);
		//echo $query;
		$dbres = $query -> execute();

		$t = $query -> fetch(PDO::FETCH_ASSOC);

		$tab = array();

		foreach ($t as $tfact) {
			$tab[] = Offre::creerObjet($t);
		}

		return $tab;

	}

	/**
	 *   Finder All
	 *
	 *   Renvoie toutes les lignes de la table Page
	 *   sous la forme d'un tableau d'objets
	 *
	 *   @static
	 *   @return Array renvoie un tableau de Page
	 */

	public static function findAll() {

		/**
		 *    A ECRIRE ENTIEREMENT
		 *    SELECTIONNE TOUTES LES LIGNES DE LA TABLE
		 *    ET LES RETOURNE SOUS LA FORME D'UN TABLEAU D'OBJETS
		 *
		 *
		 */

		$pdo = Base::getConnection();
		$query = "SELECT * FROM Offre";
		$dbres = $pdo -> query($query);
		$t = $dbres -> fetchAll(PDO::FETCH_ASSOC);

		$tab = array();

		foreach ($t as $tfact) {
			$tab[] = Offre::creerObjet($t);
		}

		return $tab;

	}

	public static function creerObjet($tab) {
		$obj = new Offre();
		$obj -> setAttr('no_unite', $tab['NO_UNITE']);
		$obj -> setAttr('sem_sjr', $tab['SEM_SEJ']);
		$obj -> setAttr('$nb_places_offertes', $tab['NB_PLACES_OFFERTES']);
		$obj -> setAttr('$nb_places_occupees', $tab['NB_PLACES_OCCUPEES']);
		return $obj;
	}

}
?>
