<?php

/*classe permettant de representer les tuples de la table client */
class Annee {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $idAnnee;
      private $nomAnnee;
      
      public function __construct($i="",$n="") {
            $this->idAnnee = $i;
	      $this->nomAnnee = $n;
      }

      public function getIdAnnee() { return $this->idAnnee; }
      public function getNomAnnee() { return $this->nomAnnee;}

      public function __toString() {
      	     $res = "nom:".$this->idAnnee."\n";
	     $res = $res ."prenom:".$this->nomAnnee."\n";
	     $res = $res ."<br/>";
	     return $res;
	     
      }
}
?>
