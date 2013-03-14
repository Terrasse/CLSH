<?php

/**
 *  La classe Facture
 *
 *  La Classe facture  realise un Active Record sur la table facture
 *  
 *
 *  @package ACSI
 */
 
class Facture{

 
  private $no_fact;
  private $date_fact;
  private $montant_fact;
  private $mode_paiement;

  
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
	if (isset($this->no_fact)){
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
    
	if (!isset($this->montant_fact)) {	
      throw new Exception(__CLASS__ . ": Montant_fact undefined : cannot update");
    } 
    
	
	$pdo = Base::getConnection();
	
	//preparation de la requete
	$query = $pdo ->prepare("update facture set montant_fact=:montant_fact, 
				where no_fact=:no_fact");
				
	//liaison des parametres
	if (isset($this->montant_fact))
			$query->bindParam(':montant_fact',$this->montant_fact);
		else
			$query->bindParam(':montant_fact',"null",PDO::PARAM_STR);
	$query->bindParam(':no_fact',$this->no_fact);
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
    
	if (isset($this->no_fact)){ 
		$delete = $pdo->prepare("DELETE FROM facture WHERE no_fact = :no_fact");
		$delete->bindParam(':no_fact',$this->no_fact);
     	
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
    $insert = $pdo->prepare("INSERT INTO facture VALUES (null, :date_fact, :montant_fact, :mode_paiement)");
    
    if (isset($this->montant_fact)){
			$insert->bindParam(':montant_fact',$this->montant_fact);
	}else{
			$insert->bindParam(':montant_fact',"null",PDO::PARAM_STR);
	}
    
    if (isset($this->mode_paiement)){
			$insert->bindParam(':mode_paiement',$this->mode_paiement);
	}else{
			$insert->bindParam(':mode_paiement',"null",PDO::PARAM_STR);
	}
	
	
	$insert->bindParam(':date_fact',date("Ymd"),PDO::PARAM_STR);
    
    $nb=$insert->execute();
    $this->setAttr('no_fact', $pdo->LastInsertId());
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
	$query =$pdo->prepare("SELECT * FROM facture WHERE no_fact=:num");
	$query->bindParam(':num',$num);
	
	$dbres = $query->execute();
	
	$d=$query->fetch(PDO::FETCH_OBJ) ;

	/**
	*   A COMPLETER : CREER UN OBJET A PARTIR DE LA LIGNE
	*   OBJET INSTANCE DE LA CLASSE Page
	*
	*/
	if ($d !== false){
   		$obj = new Facture();
    	$obj->setAttr('no_fact', $d->no_fact);
    	$obj->setAttr('montant_fact', strip_tags($d->montant_fact));
    	$obj->setAttr('mode_paiement', strip_tags($d->mode_paiement));
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
public static function findByMontant($montant) {

	$pdo = Base::getConnection();
	$query = $pdo->prepare("SELECT * FROM facture WHERE montant_fact=:montant_fact");
	$query->bindParam(":montant_fact",$montant);
	//echo $query;
	$dbres = $query->execute();
	
	$d = $query->fetch(PDO::FETCH_OBJ) ;
	
	
	/**
	*   A COMPLETER : CREER UN OBJET A PARTIR DE LA LIGNE
	*   OBJET INSTANCE DE LA CLASSE Page
	*
	*/
      
    if ($d !== false){
   		$obj = new facture();
    	$obj->setAttr('no_fact', $d->no_fact);
    	$obj->setAttr('montant_fact', strip_tags($d->montant_fact));
    	$obj->setAttr('mode_paiement', strip_tags($d->mode_paiement));
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
     $query = "SELECT * FROM facture";
     $dbres = $pdo->query($query);
     $t = $dbres->fetchAll(PDO::FETCH_OBJ) ;
          
     $tab = array();
          
     foreach ($t as $tfact){
     	$obj = new Facture();
    	$obj->setAttr('no_fact', $d->no_fact);
    	$obj->setAttr('montant_fact', strip_tags($d->montant_fact));
    	$obj->setAttr('mode_paiement', strip_tags($d->mode_paiement));
     	$tab[]=$obj;
     }
     
     return $tab;
	 
    }
}



?>
