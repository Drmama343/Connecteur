<?php

/*classe permettant de representer les tuples de la table client */
class CompSem {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $idcomp;
      private $idsem;
      
      public function __construct($i="",$n="") {
            $this->idcomp = $i;
	      $this->idsem = $n;
      }

      public function getIdComp() { return $this->idcomp; }
      public function getIdSem() { return $this->idsem;}

      public function __toString() {
	     $res = "<br/>";
	     return $res;
	     
      }
}
?>
