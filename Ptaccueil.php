<?php

/**
 *  La classe PTAccueil
 *
 *  La Classe PTAccueil  realise un Active Record sur la table PTAccueil
 *  
 *
 *  @package ACSI
 */
 
class PTAccueil{

 
  private $arret_bus;
  private $no_site;

  
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
				   prenom  ". $this->prenom . "no_fact : ". $this->no_fact;
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
	if (isset($this->arret_bus)){
		return $this->update();
	}else{
		return $this->insert();
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
    
	if (!isset($this->no_site)) {	
      throw new Exception(__CLASS__ . ": Numero du site undefined : cannot update");
    } 
    
	
	$pdo = Base::getConnection();
	
	//preparation de la requete
	$query = $pdo ->prepare("update PTAccueil set no_site =:no_site, 
				where arret_bus=:arret_bus");
				
	//liaison des parametres
	if (isset($this->no_site))
			$query->bindParam(':no_site',$this->no_site);
		else
			$query->bindParam(':no_site',"null",PDO::PARAM_STR);
	$query->bindParam(':arret_bus',$this->arret_bus);
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
    
	if (isset($this->arret_bus)){ 
		$delete = $pdo->prepare("DELETE FROM PTAccueil WHERE arret_bus = :arret_bus");
		$delete->bindParam(':arret_bus',$this->arret_bus);
     	
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
    $insert = $pdo->prepare("INSERT INTO PTAccueil VALUES (null, :no_site)");
	
	if (isset($this->no_site)){
			$insert->bindParam(':no_site',$this->no_site);
	}else{
			$insert->bindParam(':no_site',"null",PDO::PARAM_STR);
	}
    
    $nb=$insert->execute();
    $this->setAttr('arret_bus', $pdo->LastInsertId());
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
	$query =$pdo->prepare("SELECT * FROM PTAccueil WHERE arret_bus=:num");
	$query->bindParam(':num',$num);
	
	$dbres = $query->execute();
	
	$d=$query->fetch(PDO::FETCH_OBJ) ;

	/**
	*   A COMPLETER : CREER UN OBJET A PARTIR DE LA LIGNE
	*   OBJET INSTANCE DE LA CLASSE Page
	*
	*/
	if ($d !== false){
    	return PTAccueil::creerObjet($d);
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
public static function findBySite($id) {

	$pdo = Base::getConnection();
	$query = $pdo->prepare("SELECT * FROM PTAccueil WHERE no_site=:id");
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
    	return PTAccueil::creerObjet($d);
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
     $query = "SELECT * FROM PTAccueil";
     $dbres = $pdo->query($query);
     $t = $dbres->fetchAll(PDO::FETCH_OBJ) ;
          
     $tab = array();
          
     foreach ($t as $tfact){
     	$tab[]=PTAccueil::creerObjet($t);
     }
     
     return $tab;
	 
    }
    
     public static function creerObjet($tab){
		$obj = new PTAccueil();
    	$obj->setAttr('arret_bus', $tab['ARRET_BUS']);
		$obj->setAttr('no_site', $tab['NO_SITE']);
		return $obj;
	}
    
    
}



?>
