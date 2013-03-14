<?php

/**
 *  La classe inscription
 *
 *  La Classe inscription  realise un Active Record sur la table inscription
 *  
 *
 *  @package ACSI
 */
 
class inscription{

 
  private $no_inscription;
  private $no_fact;
  private $arret_bus;
  private $no_unite;
  private $no_enf;
  private $deduc_jour;
  private $nom_accompagnateur_enf;
  private $pre_accompagnateur_enf;
  private $montant_inscr;
  private $lieu_inscr;

  
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
        return "[". __CLASS__ . "] nom : ". $this->nom . "
				   prenom  ". $this->prenom . "no_fam : ". $this->no_fam;
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
    if (property_exists( __CLASS__, $attr_name)) { 
      return $this->$attr_name;
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
    if (property_exists( __CLASS__, $attr_name)) {
      $this->$attr_name=$attr_val; 
      return $this->$attr_name;
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
	if (isset($this->no_inscription)){
		return $this->update();
	}else{
		$r = self::findByEnf($this->no_enf);
		//si la page n'existe pas
		if ($r == false){
			return $this->insert();
		}else{
			//la page exite deja
			//met à jour id et on update
			$this->no_inscription = $r->no_inscription;
			return $this->update();
			}
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
    
	if (!isset($this->no_fact)) {	
      throw new Exception(__CLASS__ . ": Facture undefined : cannot update");
    } 
    
    if (!isset($this->no_unite)) {	
      throw new Exception(__CLASS__ . ": Unite undefined : cannot update");
    } 
    
    if (!isset($this->no_enf)) {	
      throw new Exception(__CLASS__ . ": Enfant undefined : cannot update");
    } 
    
	
	$pdo = Base::getConnection();
	
	//preparation de la requete
	$query = $pdo ->prepare("update Inscription set no_fact=:no_fact, no_unite=:no_unite, no_enf=:no_enf,
				where no_inscription=:no_inscription");
				
	//liaison des parametres
	if (isset($this->no_fact)){
			$insert->bindParam(':no_fact',$this->no_fact);
	}else{
			$insert->bindParam(':no_fact',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->no_unite)){
			$insert->bindParam('::no_unite',$this->no_unite);
	}else{
			$insert->bindParam('::no_unite',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->no_enf)){
			$insert->bindParam(':no_enf',$this->no_enf);
	}else{
			$insert->bindParam(':no_enf',"null",PDO::PARAM_STR);
	}
	
	$query->bindParam(':no_fam',$this->no_fam);
	//lancement de la requete prépar	  
    $nb=$query->execute();
    
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
    
	if (isset($this->no_inscription)){ 
		$delete = $pdo->prepare("DELETE FROM Inscription WHERE no_inscription = :no_inscription");
		$delete->bindParam(':no_inscription',$this->no_inscription);
     	
     	$nb = $delete->execute();  
	 }else{
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
    $insert = $pdo->prepare("INSERT INTO famille VALUES (null, :no_fact, :arret_bus, :no_unite, :no_enf, :deduc_jour, :nom_accompagnateur_enf, : pre_accompagnateur_enf, :montant_inscr, :lieu_inscr)");
    
    if (isset($this->no_fact)){
			$insert->bindParam(':no_fact',$this->no_fact);
	}else{
			$insert->bindParam(':no_fact',"null",PDO::PARAM_STR);
	}
    
    if (isset($this->arret_bus)){
			$insert->bindParam(':arret_bus',$this->arret_bus);
	}else{
			$insert->bindParam(':arret_bus',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->no_unite)){
			$insert->bindParam('::no_unite',$this->no_unite);
	}else{
			$insert->bindParam('::no_unite',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->no_enf)){
			$insert->bindParam(':no_enf',$this->no_enf);
	}else{
			$insert->bindParam(':no_enf',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->deduc_jour)){
			$insert->bindParam(':deduc_jour',$this->deduc_jour);
	}else{
			$insert->bindParam(':deduc_jour',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->nom_accompagnateur_enf)){
			$insert->bindParam(':nom_accompagnateur_enf',$this->nom_accompagnateur_enf);
	}else{
			$insert->bindParam(':nom_accompagnateur_enf',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->pre_accompagnateur_enf)){
			$insert->bindParam(':pre_accompagnateur_enf',$this->pre_accompagnateur_enf);
	}else{
			$insert->bindParam(':pre_accompagnateur_enf',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->lieu_inscr)){
			$insert->bindParam(':lieu_inscr',$this->lieu_inscr);
	}else{
			$insert->bindParam(':lieu_inscr',"null",PDO::PARAM_STR);
	}
	
	
    
    $nb=$insert->execute();
    $this->setAttr('no_inscription', $pdo->LastInsertId());
	$this->update();
	
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
public static function findByNum($num) {
	$pdo = Base::getConnection();
	
	//preparation de la requete
	$query =$pdo->prepare("SELECT * FROM Inscription WHERE no_inscription=:num");
	$query->bindParam(':num',$num);
	
	$dbres = $query->execute();
	
	$d=$query->fetch(PDO::FETCH_ASSOC) ;

	if ($d !== false){
    	return INSCRIPTION::creerObjet($d);
    }else{
    	return false;
    }
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
public static function findByEnf($id) {

	$pdo = Base::getConnection();
	$query = $pdo->prepare("SELECT * FROM Inscription WHERE no_enf=:id");
	$query->bindParam(":id",$id);
	//echo $query;
	$dbres = $query->execute();
	
	$d = $query->fetch(PDO::FETCH_OBJ) ;
	
	
	/**
	*   A COMPLETER : CREER UN OBJET A PARTIR DE LA LIGNE
	*   OBJET INSTANCE DE LA CLASSE Page
	*
	*/
      
    if ($d !== false){
   		return INSCRIPTION::creerObjet($d);
    }else{
    	return false;
    }

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
     $query = "SELECT * FROM Inscription";
     $dbres = $pdo->query($query);
     $t = $dbres->fetchAll(PDO::FETCH_ASSOC) ;
          
     $tab = array();
          
     foreach ($t as $tfam){
     	$tab[]=INSCRIPTION::creerObjet($t);
     }
     
     return $tab;
	 
    }
    
    public static function creerObjet($tab){
		$obj = new Inscription();
    	$obj->setAttr('no_inscription', $tab['NO_INSCRIPTION']);
    	$obj->setAttr('no_fact', $tab['NO_FACT']);
    	$obj->setAttr('arret_bus', $tab['ARRET_BUS']);
		$obj->setAttr('no_unite', $tab['NO_UNITE']);
		$obj->setAttr('no_enf', $tab['NO_ENF']);
		$obj->setAttr('deduc_jour', $tab['DEDUC_JOUR']);
		$obj->setAttr('nom_accompagnateur_enf', $tab['NOM_ACCOMPAGNATEUR_ENF']);
		$obj->setAttr('pre_accompagnateur_enf', $tab['PRE_ACCOMPAGNATEUR_ENF']);
		$obj->setAttr('montant_inscr', $tab['MONTANT_INSCR']);
		$obj->setAttr('lieu_inscr', $tab['LIEU_INSCR']);
		return $obj;
	}
	
	
}



?>