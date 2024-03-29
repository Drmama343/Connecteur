<?php

/*classe permettant de representer les tuples de la table client */
class Semestre {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $idsem;
      private $nomsem;
	
	public function __construct($i="",$n="") {
		$this->idsem = $i;
		$this->nomsem = $n;
	}

      public function getIdSem() { return $this->idsem; }
      public function getNomSem() { return $this->nomsem;}

      public function __toString() {
		$res = "<br/>";
		return $res;
	     
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
