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
				   prenom  ". $this->prenom . "no_inscription : ". $this->no_inscription;
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
		$r = self::findByNom($this->nom_resp);
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
    
	if (!isset($this->nom_resp)) {	
      throw new Exception(__CLASS__ . ": Title undefined : cannot update");
    } 
    
	
	$pdo = Base::getConnection();
	
	//preparation de la requete
	$query = $pdo ->prepare("update inscription set nom_resp=:nom_resp, 
				where no_inscription=:no_inscription");
				
	//liaison des parametres
	if (isset($this->nom_resp))
			$query->bindParam(':nom_resp',$this->nom_resp);
		else
			$query->bindParam(':nom_resp',"null",PDO::PARAM_STR);
	$query->bindParam(':no_inscription',$this->no_inscription);
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
		$delete = $pdo->prepare("DELETE FROM inscription WHERE no_inscription = :no_inscription");
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
    $insert = $pdo->prepare("INSERT INTO inscription VALUES (null, :nom_resp, :pre_resp, :type_resp, :adr_resp, :tel_resp, :noalloc_caf_resp, : qf_resp, :en_ville, :bons_vac)");
    
    if (isset($this->nom_resp)){
			$insert->bindParam(':nom_resp',$this->nom_resp);
	}else{
			$insert->bindParam(':nom_resp',"null",PDO::PARAM_STR);
	}
    
    if (isset($this->pre_resp)){
			$insert->bindParam(':pre_resp',$this->pre_resp);
	}else{
			$insert->bindParam(':pre_resp',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->type_resp)){
			$insert->bindParam(':type_resp',$this->type_resp);
	}else{
			$insert->bindParam(':type_resp',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->adr_resp)){
			$insert->bindParam(':adr_resp',$this->adr_resp);
	}else{
			$insert->bindParam(':adr_resp',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->tel_resp)){
			$insert->bindParam(':tel_resp',$this->tel_resp);
	}else{
			$insert->bindParam(':tel_resp',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->noalloc_caf_resp)){
			$insert->bindParam(':noalloc_caf_resp',$this->noalloc_caf_resp);
	}else{
			$insert->bindParam(':noalloc_caf_resp',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->qf_resp)){
			$insert->bindParam(':qf_resp',$this->qf_resp);
	}else{
			$insert->bindParam(':qf_resp',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->en_ville)){
			$insert->bindParam(':nom_resp',$this->nom_resp);
	}else{
			$insert->bindParam(':nom_resp',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->bons_vac)){
			$insert->bindParam(':bons_vac',$this->bons_vac);
	}else{
			$insert->bindParam(':bons_vac',"null",PDO::PARAM_STR);
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
	$query =$pdo->prepare("SELECT * FROM inscription WHERE no_inscription=:num");
	$query->bindParam(':num',$num);
	
	$dbres = $query->execute();
	
	$d=$query->fetch(PDO::FETCH_OBJ) ;

	/**
	*   A COMPLETER : CREER UN OBJET A PARTIR DE LA LIGNE
	*   OBJET INSTANCE DE LA CLASSE Page
	*
	*/
	if ($d !== false){
   		$obj = new inscription();
    	$obj->setAttr('no_inscription', $d->no_inscription);
    	$obj->setAttr('nom_resp', strip_tags($d->nom_resp));
    	$obj->setAttr('pre_resp', strip_tags($d->pre_resp));
    	return $obj;
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
public static function findByNom($nom) {

	$pdo = Base::getConnection();
	$query = $pdo->prepare("SELECT * FROM inscription WHERE nom_fam=:nom");
	$query->bindParam(":nom",$nom);
	//echo $query;
	$dbres = $query->execute();
	
	$d = $query->fetch(PDO::FETCH_OBJ) ;
	
	
	/**
	*   A COMPLETER : CREER UN OBJET A PARTIR DE LA LIGNE
	*   OBJET INSTANCE DE LA CLASSE Page
	*
	*/
      
    if ($d !== false){
   		$obj = new inscription();
    	$obj->setAttr('no_inscription', $d->no_inscription);
    	$obj->setAttr('nom_resp', strip_tags($d->nom_resp));
    	$obj->setAttr('pre_resp', strip_tags($d->pre_resp));
    	return $obj;
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
     $query = "SELECT * FROM inscription";
     $dbres = $pdo->query($query);
     $t = $dbres->fetchAll(PDO::FETCH_OBJ) ;
          
     $tab = array();
          
     foreach ($t as $tfam){
     	$obj = new inscription();
    	$obj->setAttr('no_inscription', $d->no_inscription);
    	$obj->setAttr('nom_resp', strip_tags($d->nom_resp));
    	$obj->setAttr('pre_resp', strip_tags($d->pre_resp));
     	$tab[]=$obj;
     }
     
     return $tab;
	 
    }
}



?>
