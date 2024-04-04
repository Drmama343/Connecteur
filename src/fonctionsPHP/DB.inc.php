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
		} catch (PDOException $e) { return $e; }
	}

	  /*************************************************************************
	   * Fonctions qui peuvent être utilisées dans les scripts PHP
	   *************************************************************************/

	public function MoyenneEtRangMathsParAnnee($codenip, $nomannee, $annee) {
		// Préparation de la requête pour appeler la fonction de moyenne
		$stmt_moyenne = $this->connect->prepare("SELECT MoyenneMathsParAnnee(:nip_param, :nomannee_param, :annee_param)");

		// Remplacement des paramètres de la fonction de moyenne
		$stmt_moyenne->bindParam(':nip_param', $codenip, PDO::PARAM_INT);
		$stmt_moyenne->bindParam(':nomannee_param', $nomannee, PDO::PARAM_STR);
		$stmt_moyenne->bindParam(':annee_param', $annee, PDO::PARAM_STR);

		// Exécution de la requête de moyenne
		$stmt_moyenne->execute();

		// Récupération de la moyenne
		$result['moyenne'] = $stmt_moyenne->fetchColumn();

		// Préparation de la requête pour appeler la fonction de rang
		$stmt_rang = $this->connect->prepare("SELECT RangMaths(:nip_param, :nomannee_param, :annee_param)");

		// Remplacement des paramètres de la fonction de rang
		$stmt_rang->bindParam(':nip_param', $codenip, PDO::PARAM_INT);
		$stmt_rang->bindParam(':nomannee_param', $nomannee, PDO::PARAM_STR);
		$stmt_rang->bindParam(':annee_param', $annee, PDO::PARAM_STR);

		// Exécution de la requête de rang
		$stmt_rang->execute();

		// Récupération du rang
		$result['rang'] = $stmt_rang->fetchColumn();

		return $result;
	}

	public function MoyenneEtRangAnglaisParAnnee($codenip, $nomannee, $annee) {
		// Préparation de la requête pour appeler la fonction de moyenne
		$stmt_moyenne = $this->connect->prepare("SELECT MoyenneAnglaisParAnnee(:nip_param, :nomannee_param, :annee_param)");

		// Remplacement des paramètres de la fonction de moyenne
		$stmt_moyenne->bindParam(':nip_param', $codenip, PDO::PARAM_INT);
		$stmt_moyenne->bindParam(':nomannee_param', $nomannee, PDO::PARAM_STR);
		$stmt_moyenne->bindParam(':annee_param', $annee, PDO::PARAM_STR);

		// Exécution de la requête de moyenne
		$stmt_moyenne->execute();

		// Récupération de la moyenne
		$result['moyenne'] = $stmt_moyenne->fetchColumn();

		// Préparation de la requête pour appeler la fonction de rang
		$stmt_rang = $this->connect->prepare("SELECT RangAnglais(:nip_param, :nomannee_param, :annee_param)");

		// Remplacement des paramètres de la fonction de rang
		$stmt_rang->bindParam(':nip_param', $codenip, PDO::PARAM_INT);
		$stmt_rang->bindParam(':nomannee_param', $nomannee, PDO::PARAM_STR);
		$stmt_rang->bindParam(':annee_param', $annee, PDO::PARAM_STR);

		// Exécution de la requête de rang
		$stmt_rang->execute();

		// Récupération du rang
		$result['rang'] = $stmt_rang->fetchColumn();
	}

	public function MettreAJourRangsCompetencesParAnnee($nomannee, $annee) {
		// Préparation de la requête pour appeler la fonction
		$stmt = $this->connect->prepare("SELECT MettreAJourRangsCompetencesParAnnee(:nomannee_param, :annee_param)");

		// Remplacement des paramètres de la fonction de rang
		$stmt->bindParam(':nomannee_param', $nomannee, PDO::PARAM_STR);
		$stmt->bindParam(':annee_param', $annee, PDO::PARAM_STR);

		// Exécution de la requête
		$stmt->execute();
		
		// Récupération du résultat
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	public function MettreAJourRangsSemestre($nomannee, $annee) {
		// Préparation de la requête pour appeler la fonction
		$stmt = $this->connect->prepare("SELECT MettreAJourRangsSemestre(:nomannee_param, :annee_param)");

		// Remplacement des paramètres de la fonction de rang
		$stmt->bindParam(':nomannee_param', $nomannee, PDO::PARAM_STR);
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

	public function getEtudiantsSemestreAnnee($semestre, $annee) {
		$requete = "SELECT e.* from Etudiant e JOIN JurySem j ON e.codenip = j.codenip WHERE j.anneePromo = '$annee' AND j.idSem = $semestre";
		return $this->execQuery($requete,null,'Etudiant');
	}

	public function getRessources() {
		$requete = 'SELECT * from Ressource';
		return $this->execQuery($requete,null,'Ressource');
	}

	public function getEtudiantsByCode($code) {
		$requete = "SELECT * from Etudiant where codenip = '$code'";
		return $this->execQuery($requete,null,'Etudiant');
	}

	public function getNbEtudAnneeAnnee($annee, $nomAnnee) {
		$requete = "SELECT COUNT(e.*) from Etudiant e JOIN JuryAnnee j ON e.codenip = j.codenip WHERE j.anneePromo = '$annee' AND j.nomAnnee = '$nomAnnee'";
		$stmt = $this->connect->prepare($requete);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	//fonction de toivimic 

	public function getJuryAnneeByAnnees($nomannee, $anneepromo) {
		$requete = "SELECT * from JuryAnnee WHERE anneepromo = '$anneepromo' AND nomannee = '$nomannee'";
		return $this->execQuery($requete,null,'JuryAnnee');
	}

	public function getJurySemByAnneeSem ($anneepromo, $idsem){
		$requete = "SELECT * from JurySem WHERE anneepromo = '$anneepromo' and idsem = $idsem";
		return $this->execQuery($requete,null,'JurySem');
	}

	public function getMoyRessByCodeAnneeIdRess($code, $anneepromo, $idress) {
		$requete = "SELECT * from MoyRess WHERE codenip = '$code' and anneepromo = '$anneepromo' AND idress = '$idress'";
		return $this->execQuery($requete,null,'MoyRess');
	}

	public function getAllMoyRess() {
		$requete = "SELECT DISTINCT * from MoyRess";
		return $this->execQuery($requete,null,'MoyRess');
	}

	public function getMoyCompSem($code, $competence, $semestre) {
		$requete = "SELECT * from MoyCompSem WHERE codenip = '$code' AND idcomp = '$competence' AND idsem = '$semestre'";
		return $this->execQuery($requete,null,'MoyCompSem');
	}

	public function getMoyRessByEtu($code, $idRess, $annee) {
		$requete = "SELECT * from MoyRess WHERE codenip = '$code' AND idRess = '$idRess' AND anneePromo = '$annee'";
		return $this->execQuery($requete,null,'MoyRess');
	}

	public function getMoyCompSemByCodeAnneeCompSem($code, $anneepromo, $competence, $semestre) {
		$requete = "SELECT * from MoyCompSem WHERE codenip = '$code' and anneepromo = '$anneepromo' AND idcomp = '$competence' AND idsem = '$semestre'";
		return $this->execQuery($requete,null,'MoyCompSem');
	}
	
	public function getJurySemByEtudAnneeSemByCodeAnneeIdSem ($codenip, $anneepromo, $idsem){
		$requete = "SELECT * from JurySem WHERE codenip = $codenip and anneepromo = '$anneepromo' and idsem = $idsem";
		return $this->execQuery($requete,null,'JurySem');
	}

	//fonction de frizoks
	public function getJuryAnnee($codenip, $nomannee, $annee) {
		$requete = "SELECT * from JuryAnnee WHERE codenip = $codenip AND nomannee = '$nomannee' AND anneePromo = '$annee'";
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
	public function getMoyCompAnnee($codenip, $nomannee, $annee) {
		$requete = "SELECT * from MoyCompAnnee WHERE nomAnnee = '$nomannee' AND codenip = $codenip AND anneePromo = '$annee'";
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
		$requete = "SELECT * FROM MoyCompSem mcs JOIN Etudiant e ON mcs.codenip = e.codenip WHERE idComp = '$idComp' AND idSem = '$idSem' AND mcs.codenip = $codenip";
		return $this->execQuery($requete,null,'MoyCompSem');
	}

	public function getMoyCompAnneeByComp($codenip, $nomannee, $annee, $idComp) {
		$requete = "SELECT * from MoyCompAnnee WHERE nomAnnee = '$nomannee' AND codenip = $codenip AND anneePromo = '$annee' AND idComp = '$idComp'";
		return $this->execQuery($requete,null,'MoyCompAnnee');
	}

	public function getPromotions() {
		$requete = "SELECT * FROM Promotion";
		return $this->execQuery($requete,null,'Promotion');
	}

	public function getMoyennesRessourcesParAnnee($annee) {
        $moyennes = array(); // Initialiser le tableau des moyennes
        
        // Requête SQL pour récupérer les moyennes des ressources pour une année spécifique
        $sql = "SELECT codeNip, idRess, moyRess FROM MoyRess WHERE anneePromo = :annee";

        // Préparation de la requête
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(':annee', $annee, PDO::PARAM_STR);
        
        // Exécution de la requête
        if ($stmt->execute()) {
            // Parcourir les résultats de la requête
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Stocker la moyenne dans le tableau multidimensionnel
                $moyennes[$row['codenip']][$row['idress']] = $row['moyress'];
            }
        }

        return $moyennes;
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

	public function updateMoyRess($codeNip, $annee, $idress, $moyress) {
		$requete = 'UPDATE MoyRess SET moyRess = ? WHERE codeNip = ? AND anneePromo = ? AND idRess = ?';
		$tparam = array($moyress, $codeNip, $annee, $idress);
		return $this->execMaj($requete, $tparam);
	}

	public function updateJurySem($codeNip, $annee, $idSem, $moySem, $UE, $bonus, $rang) {
		$requete = 'UPDATE JurySem SET moySem = ?, ue = ?, bonus = ?, rang = ? WHERE codeNip = ? AND anneePromo = ? AND idSem = ?';
		$tparam = array($moySem, $UE, $bonus, $rang, $codeNip, $annee, $idSem);
		return $this->execMaj($requete, $tparam);
	}

	public function updateJuryAnnee($codeNip, $annee, $nomannee, $moyannee, $rcue, $decision, $rang, $absinjust) {
		$requete = 'UPDATE JuryAnnee SET moyAnnee = ?, rcue = ?, decision = ?, rang = ?, absInjust = ? WHERE codeNip = ? AND anneePromo = ? AND nomAnnee = ?';
		$tparam = array($moyannee, $rcue, $decision, $rang, $absinjust, $codeNip, $annee, $nomannee);
		return $this->execMaj($requete, $tparam);
	}
	
	public function updateMoyCompSem($codenip, $annee, $idcomp, $idsem, $moycompsem, $avis) {
		$requete = 'UPDATE MoyCompSem SET moyCompSem = ?, avis = ? WHERE codeNip = ? AND anneePromo = ? AND idComp = ? AND idSem = ?';
		$tparam = array($moycompsem, $avis, $codenip, $annee, $idcomp, $idsem);
		return $this->execMaj($requete, $tparam);
	}

	public function updateMoyCompAnnee($codeNip, $annee, $idcomp, $nomannee, $moycompannee, $avis, $rang) {
		$requete = 'UPDATE MoyCompAnnee SET moyCompAnnee = ?, avis = ?, rang = ? WHERE codeNip = ? AND anneePromo = ? AND numComp = ? AND nomAnnee = ?';
		$tparam = array($moycompannee, $avis, $rang, $codeNip, $annee, $idcomp, $nomannee);
		return $this->execMaj($requete, $tparam);
	}

	
	/*************************************************************************
	   * Fonctions Pour Inserer des donnees dans la base
	   *************************************************************************/

	public function insertIntoPromotion($anneePromo) {
		$requete = 'INSERT INTO Promotion VALUES (?)';
		$tparam = array($anneePromo);
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
	
	public function insertIntoMoyRess($codeNip, $annee, $idRess, $moyRess) {
		$requete = 'INSERT INTO MoyRess VALUES (?, ?, ?, ?)';
		$tparam = array($codeNip, $annee, $idRess, $moyRess);
		$val = $this->execMaj($requete, $tparam);
		if($val !== 0) {return $this->updateMoyRess($codeNip, $annee, $idRess, $moyRess);}
		return $val;
	}
	
	public function insertIntoJurySem($codeNip, $annee, $idSem, $moySem, $UE, $rang, $bonus) {
		$requete = 'INSERT INTO JurySem VALUES (?, ?, ?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $annee, $idSem, $moySem, $UE, $rang, $bonus);
		$val = $this->execMaj($requete, $tparam);
		if($val !== 0) {return $this->updateJurySem($codeNip, $annee, $idSem, $moySem, $UE, $rang, $bonus);}
		return $val;
	}
	
	public function insertIntoJuryAnnee($codeNip, $annee, $nomAnnee, $moyAnnee, $RCUE, $decision, $rang, $absInjust) {
		$requete = 'INSERT INTO JuryAnnee VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $annee, $nomAnnee, $moyAnnee, $RCUE, $decision, $rang, $absInjust);
		$val = $this->execMaj($requete, $tparam);
		if($val === 0) { return $val; }
		return $this->updateJuryAnnee($codeNip, $annee, $nomAnnee, $moyAnnee, $RCUE, $decision, $rang, $absInjust);
	}
	
	public function insertIntoMoyCompSem($codeNip, $annee, $idComp, $idSem, $moyCompSem, $avis) {
		$requete = 'INSERT INTO MoyCompSem VALUES (?, ?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $annee, $idComp, $idSem, $moyCompSem, $avis);
		$val = $this->execMaj($requete, $tparam);
		if($val !== 0) {return $this->updateMoyCompSem($codeNip, $annee, $idComp, $idSem, $moyCompSem, $avis);}
		return $val;
	}
	
	public function insertIntoMoyCompAnnee($codeNip, $annee, $numComp, $idAnnee, $moyCompAnnee, $avis, $rang) {
		$requete = 'INSERT INTO MoyCompAnnee VALUES (?, ?, ?, ?, ?, ?, ?)';
		$tparam = array($codeNip, $annee, $numComp, $idAnnee, $moyCompAnnee, $avis, $rang);
		$val = $this->execMaj($requete, $tparam);
		if($val !== 0) {return $this->updateMoyCompAnnee($codeNip, $annee, $numComp, $idAnnee, $moyCompAnnee, $avis, $rang);}
		return $val;
	}
} //fin classe DB

?>