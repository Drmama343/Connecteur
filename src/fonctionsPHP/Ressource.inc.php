<?php

/*classe permettant de representer les tuples de la table client */
class Ressource {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $idress;
      private $nomress;
	
	public function __construct($i="",$n="",$a="") {
		$this->idress = $i;
		$this->nomress = $n;
	}

      public function getIdRess() { return $this->idress; }
      public function getNomRess() { return $this->nomress;}

      public function __toString() {
		$res = "<br/>";
		return $res;
	     
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
