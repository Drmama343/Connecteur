<?php

require ("Annee.inc.php");
require ("Coeff.inc.php");
require ("Competence.inc.php");
require ("CompSem.inc.php");
require ("Etudiant.inc.php");
require ("JuryAnnee.inc.php");
require ("JurySem.inc.php");
require ("MoyCompAnnee.inc.php");
require ("MoyCompSem.inc.php");
require ("MoyRess.inc.php");
require ("PromoEtud.inc.php");
require ("Promotion.inc.php");
require ("Ressource.inc.php");
require ("Semestre.inc.php");

class DB {
	  private static $instance = null; //mémorisation de l'instance de DB pour appliquer le pattern Singleton
	  private $connect=null; //connexion PDO à la base

	  /************************************************************************/
	  //	Constructeur gerant  la connexion à la base via PDO
	  //	NB : il est non utilisable a l'exterieur de la classe DB
	  /************************************************************************/	
	  private function __construct() {
	  		  // Connexion à la base de données
		  $host = "woody"; // ou l'adresse IP de votre serveur PostgreSQL
			$port = "5432"; // port par défaut pour PostgreSQL
			$dbname = "ca220584";
			$user = "ca220584";
			$password = "Miss10sur10!"; 
		  try {
		  // Connexion à la base
		  	  $this->connect = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
		  // Configuration facultative de la connexion
		  $this->connect->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); 
		  $this->connect->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION); 
		  }
		  catch (PDOException $e) {
	  		  		echo "probleme de connexion :".$e->getMessage();
			return null;    
		  }
	  }

	  /************************************************************************/
	  //	Methode permettant d'obtenir un objet instance de DB
	  //	NB : cet objet est unique pour l'exécution d'un même script PHP
	  //	NB2: c'est une methode de classe.
	  /************************************************************************/
	  public static function getInstance() {
	  		 if (is_null(self::$instance)) {
 		 	try { 
			  self::$instance = new DB(); 
 		} 
		catch (PDOException $e) {
			echo $e;
 		}
			} //fin IF
 		$obj = self::$instance;

		if (($obj->connect) == null) {
		   self::$instance=null;
		}
		return self::$instance;
	  } //fin getInstance	 

	  /************************************************************************/
	  //	Methode permettant de fermer la connexion a la base de données
	  /************************************************************************/
	  public function close() {
	  		 $this->connect = null;
	  }

	  /************************************************************************/
	  //	Methode uniquement utilisable dans les méthodes de la class DB 
	  //	permettant d'exécuter n'importe quelle requête SQL
	  //	et renvoyant en résultat les tuples renvoyés par la requête
	  //	sous forme d'un tableau d'objets
	  //	param1 : texte de la requête à exécuter (éventuellement paramétrée)
	  //	param2 : tableau des valeurs permettant d'instancier les paramètres de la requête
	  //	NB : si la requête n'est pas paramétrée alors ce paramètre doit valoir null.
	  //	param3 : nom de la classe devant être utilisée pour créer les objets qui vont
	  //	représenter les différents tuples.
	  //	NB : cette classe doit avoir des attributs qui portent le même que les attributs
	  //	de la requête exécutée.
	  //	ATTENTION : il doit y avoir autant de ? dans le texte de la requête
	  //	que d'éléments dans le tableau passé en second paramètre.
	  //	NB : si la requête ne renvoie aucun tuple alors la fonction renvoie un tableau vide
	  /************************************************************************/
      private function execQuery($requete,$tparam,$nomClasse) {
		//on prépare la requête
		$stmt = $this->connect->prepare($requete);
		//on indique que l'on va récupére les tuples sous forme d'objets instance de Client
		$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $nomClasse); 
		//on exécute la requête
		if ($tparam != null) {
			$stmt->execute($tparam);
		}
		else {
			$stmt->execute();
		}
		//récupération du résultat de la requête sous forme d'un tableau d'objets
		$tab = array();
		$tuple = $stmt->fetch(); //on récupère le premier tuple sous forme d'objet
		if ($tuple) {
			//au moins un tuple a été renvoyé
					while ($tuple != false) {
				$tab[]=$tuple; //on ajoute l'objet en fin de tableau
						$tuple = $stmt->fetch(); //on récupère un tuple sous la forme
						//d'un objet instance de la classe $nomClasse	       
			} //fin du while	           	     
			}
		return $tab;    
	}	
  
	   /************************************************************************/
	  //	Methode utilisable uniquement dans les méthodes de la classe DB
	  //	permettant d'exécuter n'importe quel ordre SQL (update, delete ou insert)
	  //	autre qu'une requête.
	  //	Résultat : nombre de tuples affectés par l'exécution de l'ordre SQL
	  //	param1 : texte de l'ordre SQL à exécuter (éventuellement paramétré)
	  //	param2 : tableau des valeurs permettant d'instancier les paramètres de l'ordre SQL
	  //	ATTENTION : il doit y avoir autant de ? dans le texte de la requête
	  //	que d'éléments dans le tableau passé en second paramètre.
	  /************************************************************************/
	  private function execMaj($ordreSQL,$tparam) {
	  		 $stmt = $this->connect->prepare($ordreSQL);
		 $res = $stmt->execute($tparam); //execution de l'ordre SQL      	     
		 return $stmt->rowCount();
	  }

	  /*************************************************************************
	   * Fonctions qui peuvent être utilisées dans les scripts PHP
	   *************************************************************************/
	  
	public function getEtudiants() {
		$requete = 'select * from Etudiant';
		return $this->execQuery($requete,null,'Etudiant');
	}
	public function getCodes() {
		$requete = 'select codeNip from Etudiant';
		return $this->execQuery($requete,null,'Etudiant');
	}
	  // public function deleteAchat($idcli,$np) {
	  //       $requete = 'delete from achat where ncli = ? and np = ?';
	//       $tparam = array($idcli,$np);
	//       return $this->execMaj($requete,$tparam);
      // }

	  public function insertIntoPromotion($anneePromo, $nbEtud) {
		$requete = 'INSERT INTO Promotion VALUES (?, ?)';
		$tparam = array($anneePromo, $nbEtud);
		return $this->execMaj($requete, $tparam);
	}

	/*************************************************************************
	   * Fonctions Pour Inserer des donnees dans la base
	   *************************************************************************/
	
	public function insertIntoEtudiant($codeNip, $nom, $prenom, $cursus, $parcours, $apprentissage, $avisInge, $avisMaster, $absInjust, $commentaire) {
		$requete = 'INSERT INTO Etudiant VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $nom, $prenom, $cursus, $parcours, $apprentissage, $avisInge, $avisMaster, $absInjust, $commentaire);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoAnnee($idAnnee, $nomAnnee) {
		$requete = 'INSERT INTO Annee VALUES (?, ?)';
		$tparam = array($idAnnee, $nomAnnee);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoSemestre($idSem, $nomSem, $idAnnee) {
		$requete = 'INSERT INTO Semestre VALUES (?, ?, ?)';
		$tparam = array($idSem, $nomSem, $idAnnee);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoCompetence($idComp, $nomComp) {
		$requete = 'INSERT INTO Competence VALUES (?, ?)';
		$tparam = array($idComp, $nomComp);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoRessource($idRess, $nomRess) {
		$requete = 'INSERT INTO Ressource VALUES (?, ?)';
		$tparam = array($idRess, $nomRess);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoPromoEtud($anneePromo, $codeNip) {
		$requete = 'INSERT INTO PromoEtud VALUES (?, ?)';
		$tparam = array($anneePromo, $codeNip);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoCoeff($idComp, $idRess, $coeff) {
		$requete = 'INSERT INTO Coeff VALUES (?, ?, ?)';
		$tparam = array($idComp, $idRess, $coeff);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoCompSem($idComp, $idSem) {
		$requete = 'INSERT INTO CompSem VALUES (?, ?)';
		$tparam = array($idComp, $idSem);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoMoyRess($codeNip, $idRess, $moyRess) {
		$requete = 'INSERT INTO MoyRess VALUES (?, ?, ?)';
		$tparam = array($codeNip, $idRess, $moyRess);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoJurySem($codeNip, $idSem, $moySem, $UE, $bonus) {
		$requete = 'INSERT INTO JurySem VALUES (?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $idSem, $moySem, $UE, $bonus);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoJuryAnnee($codeNip, $idAnnee, $moyAnnee, $RCUE, $decision) {
		$requete = 'INSERT INTO JuryAnnee VALUES (?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $idAnnee, $moyAnnee, $RCUE, $decision);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoMoyCompSem($codeNip, $idComp, $idSem, $moyCompSem, $avis) {
		$requete = 'INSERT INTO MoyCompSem VALUES (?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $idComp, $idSem, $moyCompSem, $avis);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoMoyCompAnnee($codeNip, $idComp, $idAnnee, $moyCompAnnee, $avis) {
		$requete = 'INSERT INTO MoyCompAnnee VALUES (?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $idComp, $idAnnee, $moyCompAnnee, $avis);
		return $this->execMaj($requete, $tparam);
	}
} //fin classe DB

?>