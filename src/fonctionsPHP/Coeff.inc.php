<?php

/*classe permettant de representer les tuples de la table client */
class Coeff {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $idComp;
      private $idRess;
	  private $coeff;
      
      public function __construct($i="",$n="",$j="") {
            $this->idComp = $i;
	      $this->idRess = $n;
		  $this->coeff = $j;
      }

      public function getIdComp() { return $this->idComp; }
      public function getIdRess() { return $this->idRess;}
	  public function getCoeff() { return $this->coeff;}

      public function __toString() {
      	     $res = "nom:".$this->idComp."\n";
	     $res = $res ."prenom:".$this->idRess."\n";
	     $res = $res ."<br/>";
	     return $res;
	     
      }
}
?>
