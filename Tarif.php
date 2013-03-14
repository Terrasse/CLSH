<?php

/**
 *  La classe Tarif
 *
 *  La Classe Tarif  realise un Active Record sur la table Tarif
 *  À TERMINER !!!!!!!!!!!!!!!!!
 *
 *  @package ACSI
 */

class Tarif {

	private $en_ville;
	private $code_gf;
	private $bons_vac;
	private $alloc_caf;
	private $tarif_jour;

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
		if (isset($this -> en_ville) && isset($this -> code_gf) && isset($this -> bons_vac) && isset($this -> alloc_caf)) {
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
			throw new Exception(__CLASS__ . ": Tarif undefined : cannot update");
		}

		$pdo = Base::getConnection();

		//preparation de la requete
		$query = $pdo -> prepare("update Tarif set tarif_jour=:$tarif_jour, 
				where no_unite=:no_unite AND sem_sej = :sem_sej");

		//liaison des parametres
		if (isset($this -> tarif_jour))
			$query -> bindParam(':tarif_jour', $this -> tarif_jour);
		else
			$query -> bindParam(':tarif_jour', "null", PDO::PARAM_STR);
		$delete -> bindParam(':en_ville', $this -> en_ville);
		$delete -> bindParam(':code_gf', $this -> code_gf);
		$delete -> bindParam(':bons_vac', $this -> bons_vac);
		$delete -> bindParam(':alloc_caf', $this -> alloc_caf);
		
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
			$delete = $pdo -> prepare("DELETE FROM Tarif WHERE en_ville = :en_ville AND code_gf = :code_gf AND bons_vac = :bons_vac AND alloc_caf = :alloc_caf");
			$delete -> bindParam(':en_ville', $this -> en_ville);
			$delete -> bindParam(':code_gf', $this -> code_gf);
			$delete -> bindParam(':bons_vac', $this -> bons_vac);
			$delete -> bindParam(':alloc_caf', $this -> alloc_caf);
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
		$insert = $pdo -> prepare("INSERT INTO Tarif VALUES (:en_ville, :code_gf, :bons_vac, :alloc_caf, :tarif_jour)");

		if (isset($this -> en_ville)) {
			$insert -> bindParam(':en_ville', $this -> en_ville);
		} else {
			$insert -> bindParam(':en_ville', "null", PDO::PARAM_STR);
		}

		if (isset($this -> code_gf)) {
			$insert -> bindParam(':code_gf', $this -> code_gf);
		} else {
			$insert -> bindParam(':code_gf', "null", PDO::PARAM_STR);
		}

		if (isset($this -> bons_vac)) {
			$insert -> bindParam(':bons_vac', $this -> bons_vac);
		} else {
			$insert -> bindParam(':bons_vac', "null", PDO::PARAM_STR);
		}

		if (isset($this -> alloc_caf)) {
			$insert -> bindParam(':alloc_caf', $this -> alloc_caf);
		} else {
			$insert -> bindParam(':alloc_caf', "null", PDO::PARAM_STR);
		}
		
		if (isset($this -> tarif_jour)) {
			$insert -> bindParam(':tarif_jour', $this -> tarif_jour);
		} else {
			$insert -> bindParam(':tarif_jour', "null", PDO::PARAM_STR);
		}

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
		var_dump($id);
		$pdo = Base::getConnection();

		//preparation de la requete
		$query = $pdo -> prepare("SELECT * FROM Tarif WHERE no_unite=:id");
		$query -> bindParam(':id', $id);

		$dbres = $query -> execute();

		$t = $query -> fetch(PDO::FETCH_ASSOC);

		$tab = array();
		var_dump($t);
		foreach ($t as $tfact) {
			$tab[] = Tarif::creerObjet($tfact);
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
		$query = $pdo -> prepare("SELECT * FROM Tarif WHERE sem_sej=:id");
		$query -> bindParam(":id", $id);
		//echo $query;
		$dbres = $query -> execute();

		$t = $query -> fetch(PDO::FETCH_ASSOC);

		$tab = array();

		foreach ($t as $tfact) {
			$tab[] = Tarif::creerObjet($t);
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
		$query = "SELECT * FROM Tarif";
		$dbres = $pdo -> query($query);
		$t = $dbres -> fetchAll(PDO::FETCH_ASSOC);

		$tab = array();

		foreach ($t as $tfact) {
			$tab[] = Tarif::creerObjet($t);
		}

		return $tab;

	}

	public static function creerObjet($tab) {
		$obj = new Tarif();
		$obj -> setAttr('no_unite', $tab['NO_UNITE']);
		$obj -> setAttr('sem_sej', $tab['SEM_SEJ']);
		$obj -> setAttr('nb_places_offertes', $tab['NB_PLACES_OFFERTES']);
		$obj -> setAttr('nb_places_occupees', $tab['NB_PLACES_OCCUPEES']);
		return $obj;
	}

}
?>
