<?php

/*classe permettant de representer les tuples de la table client */
class Competence {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $idComp;
      private $nomComp;
      
      public function __construct($i="",$n="") {
            $this->idComp = $i;
	      $this->nomComp = $n;
      }

      public function getIdComp() { return $this->idComp; }
      public function getNomComp() { return $this->nomComp;}

      public function __toString() {
	     $res = "<br/>";
	     return $res;
	     
      }
}
?>
