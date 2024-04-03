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
		private function execQuery($requete, $tparam, $nomClasse) {
			// on prépare la requête
			$stmt = $this->connect->prepare($requete);
			// on indique que l'on va récupérer les tuples sous forme d'objets instance de nomClasse
			$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $nomClasse);
			// on exécute la requête
			if ($tparam != null) {
				$stmt->execute($tparam);
			} else {
				$stmt->execute();
			}
			// récupération du résultat de la requête sous forme d'un tableau d'objets
			$tab = array();
			$tuple = $stmt->fetch(); // on récupère le premier tuple sous forme d'objet
			if ($tuple) {
				// au moins un tuple a été renvoyé
				while ($tuple != false) {
					// Affichage des valeurs récupérées avant conversion
					$tab[] = $tuple; // on ajoute l'objet en fin de tableau
					$tuple = $stmt->fetch(); // on récupère un tuple sous la forme
					// d'un objet instance de la classe $nomClasse
				} // fin du while
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
		try {
			$res = $stmt->execute($tparam); //execution de l'ordre SQL
			return 0;
		} catch (PDOException $e) { return 1; }
	}

	  /*************************************************************************
	   * Fonctions qui peuvent être utilisées dans les scripts PHP
	   *************************************************************************/

	public function MoyenneMathsParAnnee($codenip, $annee) {
		 // Préparation de la requête pour appeler la fonction
		 $stmt = $this->connect->prepare("SELECT MoyenneMathsParAnnee(:nip_param, :annee_param)");
    
		 // Remplacement des paramètres de la fonction
		 $stmt->bindParam(':nip_param', $codenip, PDO::PARAM_INT);
		 $stmt->bindParam(':annee_param', $annee, PDO::PARAM_STR);
		 
		 // Exécution de la requête
		 $stmt->execute();
		 
		 // Récupération du résultat
		 $result = $stmt->fetch(PDO::FETCH_ASSOC);
		 return $result;
	}

	public function MoyenneAnglaisParAnnee($codenip, $annee) {
		// Préparation de la requête pour appeler la fonction
		$stmt = $this->connect->prepare("SELECT MoyenneAnglaisParAnnee(:nip_param, :annee_param)");
   
		// Remplacement des paramètres de la fonction
		$stmt->bindParam(':nip_param', $codenip, PDO::PARAM_INT);
		$stmt->bindParam(':annee_param', $annee, PDO::PARAM_STR);
		
		// Exécution de la requête
		$stmt->execute();
		
		// Récupération du résultat
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
    }

	public function MettreAJourRangsCompetencesParAnnee($annee) {
		// Préparation de la requête pour appeler la fonction
		$stmt = $this->connect->prepare("SELECT MettreAJourRangsCompetencesParAnnee(:annee_param)");
   
		// Remplacement des paramètres de la fonction
		$stmt->bindParam(':annee_param', $annee, PDO::PARAM_STR);
		
		// Exécution de la requête
		$stmt->execute();
		
		// Récupération du résultat
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
   }
	
	public function getEtudiants() {
		$requete = 'SELECT * from Etudiant';
		return $this->execQuery($requete,null,'Etudiant');
	}

	public function getEtudiantsByCode($code) {
		$requete = "SELECT * from Etudiant where codenip = '$code'";
		return $this->execQuery($requete,null,'Etudiant');
	}

	//fonction de toivimic 

	public function getJuryAnneeByAnnees($nomannee, $anneepromo) {
		$requete = "SELECT * from JuryAnnee WHERE anneepromo = '$anneepromo' AND nomannee = '$nomannee'";
		return $this->execQuery($requete,null,'JuryAnnee');
	}

	public function getMoyRess($code, $idress) {
		$requete = "SELECT * from MoyRess WHERE codenip = '$code' AND idress = '$idress'";
		return $this->execQuery($requete,null,'MoyRess');
	}

	public function getMoyCompSem($code, $competence, $semestre) {
		$requete = "SELECT * from MoyCompSem WHERE codenip = '$code' AND idcomp = '$competence' AND idsem = '$semestre'";
		return $this->execQuery($requete,null,'MoyCompSem');
	}
	

	//fonction de frizoks
	public function getJuryAnnee($codenip, $nomannee) {
		$requete = "SELECT * from JuryAnnee WHERE codenip = $codenip AND nomannee = '$nomannee'";
		return $this->execQuery($requete,null,'JuryAnnee');
	}

	public function getJurySemByEtudSem ($codenip, $idsem){
		$requete = "SELECT * from JurySem WHERE codenip = $codenip and idsem = $idsem";
		return $this->execQuery($requete,null,'JurySem');
	}

	//fonction de frizoks
	public function getEtudiantsS5($annee) {
		$requete = "SELECT e.* from Etudiant e Join JuryAnnee ja ON e.codenip = ja.codenip WHERE nomAnnee = 'BUT3' AND anneePromo = '$annee'";
		return $this->execQuery($requete,null,'Etudiant');
	}

	//fonction de frizoks
	public function getMoyCompAnnee($codenip, $nomannee) {
		$requete = "SELECT * from MoyCompAnnee WHERE nomAnnee = '$nomannee' AND codenip = $codenip";
		return $this->execQuery($requete,null,'MoyCompAnnee');
	}

	public function getRangCompAnnee($codenip, $idComp, $nomAnnee) {
		$requete = "SELECT mca.moyCompAnnee from MoyCompAnnee mca Join Etudiant e ON mca.codenip = e.codenip WHERE nomAnnee = '$nomAnnee' AND idComp = '$idComp'";
		return $this->execQuery($requete,null,'Etudiant');
	}

	// les fonctions du ydro qui a besoin d'un stage
	public function getCompBySem($idSem) {
		$requete = "SELECT * FROM CompSem WHERE idSem = '$idSem'";
		return $this->execQuery($requete,null,'CompSem');
	}
	
	public function getAvisSem($codenip, $idComp, $idSem) {
		$requete = "SELECT * FROM MoyCompSem mcs JOIN Etudiant e ON mcs.codenip = e.codenip WHERE idComp = '$idComp' AND idSem = '$idSem'";
		return $this->execQuery($requete,null,'Etudiant');
	}

	  // public function deleteAchat($idcli,$np) {
	  //       $requete = 'delete from achat where ncli = ? and np = ?';
	//       $tparam = array($idcli,$np);
	//       return $this->execMaj($requete,$tparam);
      // }

	public function updateEtudiant($codeNip, $nom, $prenom, $cursus, $parcours, $apprentissage, $avisInge, $avisMaster, $commentaire, $etranger) {
		$requete = 'UPDATE Etudiant SET nom = ?, prenom = ?, cursus = ?, parcours = ?, apprentissage = ?, avisInge = ?, avisMaster = ?, commentaire = ?, mobEtrang = ? WHERE codeNip = ?';
		$tparam = array($nom, $prenom, $cursus, $parcours, $apprentissage, $avisInge, $avisMaster, $commentaire, $etranger, $codeNip);
		return $this->execMaj($requete, $tparam);
	}

	public function updateJuryAnnee($codeNip, $nomannee, $moyannee, $rcue, $decision, $rang, $anneepromo, $absinjust) {
		$requete = 'UPDATE JuryAnnee SET nomannee = ?, moyannee = ?, rcue = ?, decision = ?, rang = ?, anneepromo = ?, absinjust = ? WHERE codeNip = ?';
		$tparam = array($nomannee, $moyannee, $rcue, $decision, $rang, $anneepromo, $absinjust, $codeNip);
		return $this->execMaj($requete, $tparam);
	}

	public function updateMoyCompAnnee($codeNip, $idcomp, $nomannee, $moycompannee, $avis) {
		$requete = 'UPDATE MoyCompAnnee SET idcomp = ?, nomannee = ?, moycompannee = ?, avis = ? WHERE codeNip = ?';
		$tparam = array($idcomp, $nomannee, $moycompannee, $avis, $codeNip);
		return $this->execMaj($requete, $tparam);
	}

	
	/*************************************************************************
	   * Fonctions Pour Inserer des donnees dans la base
	   *************************************************************************/

	public function insertIntoPromotion($anneePromo, $nbEtud) {
		$requete = 'INSERT INTO Promotion VALUES (?, ?)';
		$tparam = array($anneePromo, $nbEtud);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoEtudiant($codeNip, $nom, $prenom, $cursus, $parcours, $apprentissage, $avisInge, $avisMaster, $commentaire, $mobEtrang) {
		$requete = 'INSERT INTO Etudiant VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $nom, $prenom, $cursus, $parcours, $apprentissage, $avisInge, $avisMaster, $commentaire, $mobEtrang);
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
	
	public function insertIntoCompetence($idComp, $nomComp, $numComp) {
		$requete = 'INSERT INTO Competence VALUES (?, ?, ?)';
		$tparam = array($idComp, $nomComp, $numComp);
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
	
	public function insertIntoJurySem($codeNip, $idSem, $moySem, $UE, $rang, $bonus) {
		$requete = 'INSERT INTO JurySem VALUES (?, ?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $idSem, $moySem, $UE, $rang, $bonus);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoJuryAnnee($codeNip, $idAnnee, $moyAnnee, $RCUE, $decision, $rang, $anneepromo, $absInjust) {
		$requete = 'INSERT INTO JuryAnnee VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $idAnnee, $moyAnnee, $RCUE, $decision, $rang, $anneepromo, $absInjust);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoMoyCompSem($codeNip, $idComp, $idSem, $moyCompSem, $avis) {
		$requete = 'INSERT INTO MoyCompSem VALUES (?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $idComp, $idSem, $moyCompSem, $avis);
		return $this->execMaj($requete, $tparam);
	}
	
	public function insertIntoMoyCompAnnee($codeNip, $numComp, $idAnnee, $moyCompAnnee, $avis, $rang) {
		$requete = 'INSERT INTO MoyCompAnnee VALUES (?, ?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $numComp, $idAnnee, $moyCompAnnee, $avis, $rang);
		return $this->execMaj($requete, $tparam);
	}
} //fin classe DB

?>