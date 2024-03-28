<?php

/*classe permettant de representer les tuples de la table client */
class CompSem {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $idComp;
      private $idSem;
      
      public function __construct($i="",$n="") {
            $this->idComp = $i;
	      $this->idSem = $n;
      }

      public function getIdComp() { return $this->idComp; }
      public function getIdSem() { return $this->idSem;}

      public function __toString() {
	     $res = "<br/>";
	     return $res;
	     
      }
}
?>
