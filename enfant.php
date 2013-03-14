<?php

/**
 *  La classe Enfant
 *
 *  La Classe Enfant  realise un Active Record sur la table enfant
 *  
 *
 *  @package ACSI
 */
 
class Enfant {

 
  private $numero;
  private $numero_famille;
  private $nom;
  private $prenom;
  private $adresse;
  private $sexe;
  private $date_naissance;
  private $lieu_naissance;

  
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
				   prenom  ". $this->prenom . "numero : ". $this->numero;
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
	if (isset($this->numero)){
		return $this->update();
	}else{
		$r = self::findByNom($this->nom);
		//si la page n'existe pas
		if ($r == false){
			return $this->insert();
		}else{
			//la page exite deja
			//met à jour id et on update
			$this->numero = $r->numero;
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
    
	if (!isset($this->nom)) {	
      throw new Exception(__CLASS__ . ": Title undefined : cannot update");
    } 
    
	
	$pdo = Base::getConnection();
	
	//preparation de la requete
	$query = $pdo ->prepare("update enfant set nom=:nom, 
				where no_enf=:numero");
				
	//liaison des parametres
	if (isset($this->nom))
			$query->bindParam(':nom',$this->nom);
		else
			$query->bindParam(':nom',"null",PDO::PARAM_STR);
	$query->bindParam(':numero',$this->numero);
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
    
	if (isset($this->numero)){ 
		$delete = $pdo->prepare("DELETE FROM enfant WHERE no_enf = :numero");
		$delete->bindParam(':numero',$this->numero);
     	
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
    $insert = $pdo->prepare("INSERT INTO enfant VALUES (null, :numero_famille, :nom, :prenom, :adresse, :sexe, :date_naiss, : lieu_naiss)");
    
    if (isset($this->numero_famille)){
			$insert->bindParam(':numero_famille',$this->numero_famille);
	}else{
			$insert->bindParam(':numero_famille',"null",PDO::PARAM_STR);
	}
    
    if (isset($this->nom)){
			$insert->bindParam(':nom',$this->nom);
	}else{
			$insert->bindParam(':nom',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->prenom)){
			$insert->bindParam(':prenom',$this->prenom);
	}else{
			$insert->bindParam(':prenom',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->sexe)){
			$insert->bindParam(':sexe',$this->sexe);
	}else{
			$insert->bindParam(':sexe',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->adresse)){
			$insert->bindParam(':adresse',$this->adresse);
	}else{
			$insert->bindParam(':adresse',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->date_naissance)){
			$insert->bindParam(':date_naiss',$this->date_naissance);
	}else{
			$insert->bindParam(':date_naiss',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->lieu_naissance)){
			$insert->bindParam(':lieu_naiss',$this->lieu_naissance);
	}else{
			$insert->bindParam(':lieu_naiss',"null",PDO::PARAM_STR);
	}
	
    
    $nb=$insert->execute();
    $this->setAttr('numero', $pdo->LastInsertId());
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
	$query =$pdo->prepare("SELECT * FROM enfant WHERE no_enf=:num");
	$query->bindParam(':num',$num);
	
	$dbres = $query->execute();
	
	$d=$query->fetch(PDO::FETCH_OBJ) ;

	/**
	*   A COMPLETER : CREER UN OBJET A PARTIR DE LA LIGNE
	*   OBJET INSTANCE DE LA CLASSE Page
	*
	*/
	if ($d !== false){
   		$obj = new Enfant();
    	$obj->setAttr('numero', $d->numero);
    	$obj->setAttr('nom', strip_tags($d->nom));
    	$obj->setAttr('prenom', strip_tags($d->prenom));
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
	$query = $pdo->prepare("SELECT * FROM enfant WHERE nom_enf=:nom");
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
   		$obj = new Enfant();
    	$obj->setAttr('numero', $d->numero);
    	$obj->setAttr('nom', strip_tags($d->nom));
    	$obj->setAttr('prenom', strip_tags($d->prenom));
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
     $query = "SELECT * FROM enfant";
     $dbres = $pdo->query($query);
     $t = $dbres->fetchAll(PDO::FETCH_OBJ) ;
          
     $tab = array();
          
     foreach ($t as $tenf){
     	$obj = new Enfant();
    	$obj->setAttr('numero', $d->numero);
    	$obj->setAttr('nom', strip_tags($d->nom));
    	$obj->setAttr('prenom', strip_tags($d->prenom));
     	$tab[]=$obj;
     }
     
     return $tab;
	 
    }
}



?>
