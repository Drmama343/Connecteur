<?php

/*classe permettant de representer les tuples de la table client */
class Coeff {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $idcomp;
      private $idress;
	  private $coeff;
      
      public function __construct($i="",$n="",$j="") {
            $this->idcomp = $i;
	      $this->idress = $n;
		  $this->coeff = $j;
      }

      public function getIdComp() { return $this->idcomp; }
      public function getIdRess() { return $this->idcess;}
	  public function getCoeff() { return $this->coeff;}

      public function __toString() {
      	     $res = "nom:".$this->idcomp."\n";
	     $res = $res ."prenom:".$this->idress."\n";
	     $res = $res ."<br/>";
	     return $res;
	     
      }
}
?>
