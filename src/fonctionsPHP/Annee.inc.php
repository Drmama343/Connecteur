<?php

/*classe permettant de representer les tuples de la table client */
class Annee {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $idAnnee;
      private $nomAnnee;
      private $semestre1;
      private $semestre2;
      
      public function __construct($i="",$n="", $s1="",$s2="") {
            $this->idAnnee = $i;
	      $this->nomAnnee = $n;
            $this->semestre1 = $s1;
	      $this->semestre2 = $s2;
      }

      public function getIdAnnee() { return $this->idAnnee; }
      public function getNomAnnee() { return $this->nomAnnee;}
      public function getSemestre1() { return $this->semestre1;}
      public function getSemestre2() { return $this->semestre2;}

      public function __toString() {
      	     $res = "nom:".$this->idAnnee."\n";
	     $res = $res ."prenom:".$this->nomAnnee."\n";
           $res = $res ."semestre1:".$this->semestre1."\n";
           $res = $res ."semestre2:".$this->semestre2."\n";
	     $res = $res ."<br/>";
	     return $res;
	     
      }
}
?>
