<?php

/**
 *  La classe Semaine
 *
 *  La Classe Semaine  realise un Active Record sur la table Semaine
 *  
 *
 *  @package ACSI
 */
 
class Semaine{

 
  private $sem_sej;
  private $du_sem;
  private $au_sem;
  private $nbj_sem;

  
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
	if (isset($this->sem_sej)){
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
    
	if (!isset($this->nbj_sem)) {	
      throw new Exception(__CLASS__ . ": Nombre de jours undefined : cannot update");
    }
    
	
	$pdo = Base::getConnection();
	
	//preparation de la requete
	$query = $pdo ->prepare("update Semaine set nbj_sem=:nbj_sem, 
				where sem_sej=:sem_sej");
				
	//liaison des parametres
	if (isset($this->montant_fact))
			$query->bindParam(':nbj_sem',$this->nbj_sem);
		else
			$query->bindParam(':nbj_sem',"null",PDO::PARAM_STR);
	$query->bindParam(':sem_sej',$this->no_fact);
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
		$delete = $pdo->prepare("DELETE FROM Semaine WHERE sem_sej = :sem_sej");
		$delete->bindParam(':sem_sej',$this->sem_sej);
     	
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
    $insert = $pdo->prepare("INSERT INTO Semaine VALUES (null, :du_sem, :au_sem, :nbj_sem)");
    
    if (isset($this->du_sem)){
			$insert->bindParam(':du_sem',$this->du_sem);
	}else{
			$insert->bindParam(':du_sem',"null",PDO::PARAM_STR);
	}
    
    if (isset($this->au_sem)){
			$insert->bindParam(':au_sem',$this->au_sem);
	}else{
			$insert->bindParam(':au_sem',"null",PDO::PARAM_STR);
	}
	
	if (isset($this->nbj_sem)){
			$insert->bindParam(':nbj_sem',$this->nbj_sem);
	}else{
			$insert->bindParam(':nbj_sem',"null",PDO::PARAM_STR);
	}
    
    $nb=$insert->execute();
    $this->setAttr('sem_sej', $pdo->LastInsertId());
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
	$query =$pdo->prepare("SELECT * FROM semaine WHERE sem_sej=:num");
	$query->bindParam(':num',$num);
	
	$dbres = $query->execute();
	
	$t=$query->fetch(PDO::FETCH_ASSOC) ;
	
	/**
	*   A COMPLETER : CREER UN OBJET A PARTIR DE LA LIGNE
	*   OBJET INSTANCE DE LA CLASSE Page
	*
	*/
	return Semaine::creerObjet($t);
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
     $query = "SELECT * FROM Semaine";
     $dbres = $pdo->query($query);
     $t = $dbres->fetchAll(PDO::FETCH_ASSOC) ;
          
     $tab = array();
          
     foreach ($t as $tfact){
     	$tab[]=Semaine::creerObjet($tfact);
     }
     
     return $tab;
	 
    }
    
     public static function creerObjet($tab){
		$obj = new Semaine();
    	$obj->setAttr('sem_sej', $tab['SEM_SEJ']);
    	$obj->setAttr('du_sem', $tab['DU_SEM']);
    	$obj->setAttr('au_sem', $tab['AU_EM']);
		$obj->setAttr('nbj_sem', $tab['NBJ_SEM']);
		return $obj;
	}
    
    
}



?>
